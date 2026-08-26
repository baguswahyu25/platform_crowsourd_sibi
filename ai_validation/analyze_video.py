import cv2
import numpy as np
import json
import sys
import os

def analyze_advanced_video(video_path):
    if not os.path.exists(video_path):
        return {
            "success": False,
            "message": "File video tidak ditemukan pada server."
        }

    cap = cv2.VideoCapture(video_path)

    if not cap.isOpened():
        return {
            "success": False,
            "message": "Tidak dapat membuka file video. Format berkas mungkin terdeteksi rusak."
        }

    # Informasi Dimensi Video
    raw_width = int(cap.get(cv2.CAP_PROP_FRAME_WIDTH))
    raw_height = int(cap.get(cv2.CAP_PROP_FRAME_HEIGHT))
    fps = cap.get(cv2.CAP_PROP_FPS)

    if fps <= 0 or np.isnan(fps):
        fps = 30.0

    # Normalisasi Nilai FPS (misal 29.97/29.99 -> 30.0, 59.94 -> 60.0)
    rounded_fps = round(float(fps), 2)
    if 29.0 <= rounded_fps <= 30.5:
        display_fps = 30.0
    elif 58.0 <= rounded_fps <= 61.0:
        display_fps = 60.0
    else:
        display_fps = rounded_fps

    # Deteksi Orientasi & Smart Resolution (Mendukung Video HP Potret & Lanskap)
    max_dim = max(raw_width, raw_height)
    min_dim = min(raw_width, raw_height)

    if max_dim < 600 and min_dim < 400:
        resolution_status = "Rendah"
    elif max_dim <= 1280 or min_dim <= 720:
        resolution_status = "Standar"
    else:
        resolution_status = "Tinggi"

    frame_count = 0
    freeze_count = 0

    blur_scores = []
    brightness_scores = []

    prev_gray = None

    # Loop analisis frame-by-frame
    while True:
        ret, frame = cap.read()

        if not ret or frame is None:
            break

        frame_count += 1

        # Konversi ke Grayscale untuk analisis intensitas cahaya dan ketajaman
        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)

        # 1. Analisis Kecerahan (Mean Brightness Intensity 0 - 255)
        brightness = float(np.mean(gray))
        brightness_scores.append(brightness)

        # 2. Analisis Ketajaman (Laplacian Variance Score)
        laplacian_var = float(cv2.Laplacian(gray, cv2.CV_64F).var())
        blur_scores.append(laplacian_var)

        # 3. Analisis Kelancaran Video (Hanya mendeteksi freeze frame murni yang benar-benar macet)
        if prev_gray is not None:
            try:
                # Menilai perbedaan piksel absolut antara 2 frame berturut-turut
                diff = cv2.absdiff(gray, prev_gray)
                mean_diff = float(np.mean(diff))
                
                # Jika perbedaan rata-rata piksel < 0.2, frame dianggap benar-benar macet (frozen)
                if mean_diff < 0.2:
                    freeze_count += 1
            except Exception:
                pass

        prev_gray = gray

    cap.release()

    if frame_count == 0:
        return {
            "success": False,
            "message": "Tidak ada frame video yang dapat dibaca."
        }

    # Hitung nilai rata-rata analisis
    avg_brightness = float(np.mean(brightness_scores)) if brightness_scores else 0.0
    avg_blur = float(np.mean(blur_scores)) if blur_scores else 0.0
    freeze_percentage = float((freeze_count / max(frame_count - 1, 1)) * 100)

    # 1. Kriteria Kecerahan (Toleran untuk Pencahayaan Ruangan Normal)
    if avg_brightness < 45.0:
        brightness_status = "Terlalu Gelap"
    elif avg_brightness > 210.0:
        brightness_status = "Terlalu Terang"
    else:
        brightness_status = "Normal"

    # 2. Kriteria Ketajaman (Blur Variance Threshold)
    if avg_blur < 50.0:
        blur_status = "Buram"
    else:
        blur_status = "Tajam"

    # 3. Kriteria Kelancaran Video (Batas Toleran untuk Gerakan Isyarat SIBI)
    if freeze_percentage > 35.0:
        freeze_status = "Patah-patah"
    elif freeze_percentage > 15.0:
        freeze_status = "Ada Gejala Lag"
    else:
        freeze_status = "Lancar"

    # Penentuan Kelulusan Akhir AI
    passed = (
        brightness_status == "Normal" and
        blur_status == "Tajam" and
        freeze_status != "Patah-patah" and
        resolution_status != "Rendah"
    )

    return {
        "success": True,

        "brightness": {
            "score": round(avg_brightness, 2),
            "status": brightness_status
        },

        "blur": {
            "score": round(avg_blur, 2),
            "status": blur_status
        },

        "freeze": {
            "percentage": round(freeze_percentage, 2),
            "status": freeze_status
        },

        "video": {
            "width": raw_width,
            "height": raw_height,
            "fps": display_fps,
            "resolution_status": resolution_status
        },

        "validation_status": "passed" if passed else "failed"
    }


if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"success": False, "message": "Argumen path video tidak ditemukan."}))
        sys.exit(1)

    video_path = sys.argv[1]
    result = analyze_advanced_video(video_path)
    print(json.dumps(result))
