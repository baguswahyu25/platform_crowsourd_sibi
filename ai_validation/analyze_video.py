import cv2
import numpy as np
import json
import sys


def analyze_advanced_video(video_path, blur_threshold=100, freeze_threshold=0.98):
    cap = cv2.VideoCapture(video_path)

    if not cap.isOpened():
        return {
            "success": False,
            "message": "Tidak dapat membuka file video."
        }

    # Informasi video
    width = int(cap.get(cv2.CAP_PROP_FRAME_WIDTH))
    height = int(cap.get(cv2.CAP_PROP_FRAME_HEIGHT))
    fps = cap.get(cv2.CAP_PROP_FPS)

    # Status resolusi
    if height < 480:
        resolution_status = "Rendah"
    elif height <= 720:
        resolution_status = "Standar"
    else:
        resolution_status = "Tinggi"

    frame_count = 0
    freeze_count = 0

    blur_scores = []
    brightness_scores = []

    prev_gray = None

    while True:
        ret, frame = cap.read()

        if not ret:
            break

        frame_count += 1

        gray = cv2.cvtColor(
            frame,
            cv2.COLOR_BGR2GRAY
        )

        # 1. Brightness
        brightness = np.mean(gray)
        brightness_scores.append(float(brightness))

        # 2. Blur
        blur_score = cv2.Laplacian(
            gray,
            cv2.CV_64F
        ).var()

        blur_scores.append(float(blur_score))

        # 3. Freeze frame
        if prev_gray is not None:
            res = cv2.matchTemplate(
                gray,
                prev_gray,
                cv2.TM_CCOEFF_NORMED
            )[0][0]

            if res > freeze_threshold:
                freeze_count += 1

        prev_gray = gray

    cap.release()

    # Rata-rata
    avg_blur = np.mean(blur_scores) if blur_scores else 0

    avg_brightness = (
        np.mean(brightness_scores)
        if brightness_scores
        else 0
    )

    freeze_percentage = (
        (freeze_count / frame_count) * 100
        if frame_count > 1
        else 0
    )

    # Status blur
    blur_status = (
        "Tajam"
        if avg_blur > blur_threshold
        else "Buram"
    )

    # Status brightness
    if avg_brightness < 40:
        brightness_status = "Terlalu Gelap"
    elif avg_brightness > 220:
        brightness_status = "Terlalu Terang"
    else:
        brightness_status = "Normal"

    # Status freeze
    if freeze_percentage > 10:
        freeze_status = "Patah-patah"
    elif freeze_percentage > 2:
        freeze_status = "Ada Gejala Lag"
    else:
        freeze_status = "Lancar"

    # Tentukan kelulusan
    passed = (
        brightness_status == "Normal"
        and blur_status == "Tajam"
        and freeze_percentage <= 2
        and height >= 480
    )

    return {
        "success": True,

        "brightness": {
            "score": round(float(avg_brightness), 2),
            "status": brightness_status
        },

        "blur": {
            "score": round(float(avg_blur), 2),
            "status": blur_status
        },

        "freeze": {
            "percentage": round(
                float(freeze_percentage),
                2
            ),
            "status": freeze_status
        },

        "video": {
            "width": width,
            "height": height,
            "fps": round(float(fps), 2),
            "resolution_status": resolution_status
        },

        "validation_status": (
            "passed"
            if passed
            else "failed"
        )
    }


if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"success": False, "message": "Argumen path video tidak ditemukan."}))
        sys.exit(1)

    video_path = sys.argv[1]
    result = analyze_advanced_video(video_path)
    print(json.dumps(result))
