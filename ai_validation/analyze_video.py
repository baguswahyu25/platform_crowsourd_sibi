import cv2
import numpy as np
import json
import sys
import os
import subprocess

def analyze_advanced_video(video_path):
    # 1. Pastikan berkas video fisik benar-benar ada sebelum OpenCV dibuka
    if not os.path.exists(video_path):
        return {
            "success": False,
            "message": "File video tidak ditemukan. Validasi tidak dapat dilakukan."
        }

    cap = cv2.VideoCapture(video_path)

    if not cap.isOpened():
        return {
            "success": False,
            "message": "Tidak dapat membuka file video. Format berkas mungkin terdeteksi rusak atau tidak didukung."
        }

    # 2. Pembacaan Resolusi & FPS dari Berkas Asli
    raw_width = int(cap.get(cv2.CAP_PROP_FRAME_WIDTH))
    raw_height = int(cap.get(cv2.CAP_PROP_FRAME_HEIGHT))
    fps = cap.get(cv2.CAP_PROP_FPS)

    # Fallback FFprobe jika OpenCV membaca 0.0 atau NaN FPS
    if fps <= 0 or np.isnan(fps):
        try:
            cmd = [
                'ffprobe', '-v', 'error',
                '-select_streams', 'v:0',
                '-show_entries', 'stream=r_frame_rate',
                '-of', 'default=noprint_wrappers=1:nokey=1',
                video_path
            ]
            output_fps = subprocess.check_output(cmd, stderr=subprocess.STDOUT).decode().strip()
            if '/' in output_fps:
                num, den = output_fps.split('/')
                fps = float(num) / float(den) if float(den) > 0 else 0.0
            else:
                fps = float(output_fps)
        except Exception:
            fps = 0.0

    if fps <= 0 or np.isnan(fps):
        fps_status = "Tidak Dapat Diverifikasi"
        display_fps = 0.0
    else:
        display_fps = round(float(fps), 2)
        if display_fps >= 24.0:
            fps_status = "Sesuai Standar"
        else:
            fps_status = "Di Bawah Standar"

    # Evaluasi Smart Resolution SIBI Dataset Standard (Mendukung Potret HP & Lanskap, min 400px / 600px)
    max_dim = max(raw_width, raw_height)
    min_dim = min(raw_width, raw_height)

    if max_dim < 600 or min_dim < 400:
        resolution_status = "Di Bawah Standar"
    else:
        resolution_status = "Sesuai Standar"

    frame_count = 0
    dark_frames = 0
    bright_frames = 0
    blurry_frames = 0
    freeze_frames = 0
    consecutive_freeze = 0
    freeze_events_count = 0

    blur_scores = []
    brightness_scores = []
    subject_brightness_scores = []
    prev_gray = None

    # Loop analisis frame-by-frame terhadap berkas asli
    while True:
        ret, frame = cap.read()

        if not ret or frame is None:
            break

        frame_count += 1
        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
        h, w = gray.shape

        # 1. Parameter Pencahayaan (Global & Subject Region ROI Analysis)
        # Ekstrak Area Subjek Utama (Tengah 60% lebar, 10%-90% tinggi tempat tubuh & tangan berada)
        subject_roi = gray[int(h*0.1):int(h*0.9), int(w*0.2):int(w*0.8)]

        full_brightness = float(np.mean(gray))
        subject_brightness = float(np.mean(subject_roi)) if subject_roi.size > 0 else full_brightness

        # Proporsi piksel gelap (< 60) pada area subjek
        dark_pixel_ratio_subject = float(np.sum(subject_roi < 60) / max(subject_roi.size, 1)) if subject_roi.size > 0 else 0.0

        brightness_scores.append(full_brightness)
        subject_brightness_scores.append(subject_brightness)

        # Klasifikasi frame GELAP atau TERANG berdasarkan area subjek & global
        if subject_brightness < 65.0 or full_brightness < 55.0 or dark_pixel_ratio_subject > 0.40:
            dark_frames += 1
        elif subject_brightness > 215.0 or full_brightness > 215.0:
            bright_frames += 1

        # 2. Parameter Ketajaman (Laplacian Variance Score)
        laplacian_var = float(cv2.Laplacian(gray, cv2.CV_64F).var())
        blur_scores.append(laplacian_var)
        if laplacian_var < 50.0:
            blurry_frames += 1

        # 3. Parameter Kelancaran (Membedakan subjek diam vs video freeze murni)
        if prev_gray is not None:
            try:
                diff = cv2.absdiff(gray, prev_gray)
                mean_diff = float(np.mean(diff))

                if mean_diff < 0.12:
                    freeze_frames += 1
                    consecutive_freeze += 1
                    # Jika freeze berturut-turut lebih dari 1 detik penuh
                    threshold_freeze_fps = int(display_fps) if display_fps > 0 else 30
                    if consecutive_freeze >= threshold_freeze_fps:
                        freeze_events_count += 1
                else:
                    consecutive_freeze = 0
            except Exception:
                pass

        prev_gray = gray

    cap.release()

    if frame_count == 0:
        return {
            "success": False,
            "message": "Tidak ada frame video yang dapat dibaca."
        }

    # Perhitungan Statistik Rata-rata & Rasio Distribusi
    avg_brightness = float(np.mean(brightness_scores)) if brightness_scores else 0.0
    avg_subject_brightness = float(np.mean(subject_brightness_scores)) if subject_brightness_scores else avg_brightness
    avg_blur = float(np.mean(blur_scores)) if blur_scores else 0.0

    dark_ratio = dark_frames / frame_count
    bright_ratio = bright_frames / frame_count
    blur_ratio = blurry_frames / frame_count
    freeze_percentage = float((freeze_frames / max(frame_count - 1, 1)) * 100)

    # Classification 1: Pencahayaan (Distribusi Frame Gelap / Terang)
    if dark_ratio >= 0.25 or avg_subject_brightness < 65.0 or avg_brightness < 55.0:
        brightness_status = "Terlalu Gelap"
        brightness_passed = False
        brightness_message = f"Pencahayaan pada area subjek terlalu gelap (Kecerahan subjek: {round(avg_subject_brightness, 1)}, {round(dark_ratio*100, 1)}% frame kurang cahaya)."
    elif bright_ratio >= 0.25 or avg_subject_brightness > 215.0 or avg_brightness > 215.0:
        brightness_status = "Terlalu Terang"
        brightness_passed = False
        brightness_message = "Pencahayaan terlalu terang / overexposed."
    else:
        brightness_status = "Normal"
        brightness_passed = True
        brightness_message = "Pencahayaan normal dan merata pada area subjek."

    # Classification 2: Ketajaman
    if blur_ratio > 0.35 or avg_blur < 50.0:
        blur_status = "Buram"
        blur_passed = False
    else:
        blur_status = "Tajam"
        blur_passed = True

    # Classification 3: Kelancaran
    if freeze_events_count > 0 or freeze_percentage > 25.0:
        freeze_status = "Patah-patah"
        freeze_passed = False
    elif freeze_percentage > 10.0:
        freeze_status = "Ada Gejala Lag"
        freeze_passed = True
    else:
        freeze_status = "Lancar"
        freeze_passed = True

    # Kegagalan Per-Parameter
    failure_reasons = []
    if not brightness_passed:
        failure_reasons.append(brightness_message)

    if not blur_passed:
        failure_reasons.append("Video terlalu buram / tidak fokus.")

    if not freeze_passed:
        failure_reasons.append("Video mengalami freeze / patah-patah secara signifikan.")

    if resolution_status == "Di Bawah Standar":
        failure_reasons.append("Resolusi video di bawah standar minimal.")

    if fps_status != "Sesuai Standar":
        failure_reasons.append("Frame rate (FPS) video di bawah standar minimal (24 FPS).")

    passed = (len(failure_reasons) == 0)

    return {
        "success": True,

        "brightness": {
            "score": round(avg_brightness, 2),
            "subject_score": round(avg_subject_brightness, 2),
            "dark_frame_percentage": round(dark_ratio * 100, 1),
            "min": round(float(np.min(brightness_scores)), 2) if brightness_scores else 0.0,
            "max": round(float(np.max(brightness_scores)), 2) if brightness_scores else 0.0,
            "status": brightness_status,
            "passed": brightness_passed,
            "message": brightness_message
        },

        "blur": {
            "score": round(avg_blur, 2),
            "blur_percentage": round(blur_ratio * 100, 1),
            "status": blur_status,
            "passed": blur_passed
        },

        "freeze": {
            "percentage": round(freeze_percentage, 2),
            "status": freeze_status,
            "passed": freeze_passed
        },

        "resolution": {
            "width": raw_width,
            "height": raw_height,
            "status": resolution_status,
            "passed": (resolution_status == "Sesuai Standar")
        },

        "fps": {
            "fps": display_fps,
            "status": fps_status,
            "passed": (fps_status == "Sesuai Standar")
        },

        "video": {
            "width": raw_width,
            "height": raw_height,
            "fps": display_fps,
            "fps_status": fps_status,
            "resolution_status": resolution_status
        },

        "validation_status": "passed" if passed else "failed",
        "failure_reasons": failure_reasons
    }


if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"success": False, "message": "Argumen path video tidak ditemukan."}))
        sys.exit(1)

    video_path = sys.argv[1]
    result = analyze_advanced_video(video_path)
    print(json.dumps(result))
