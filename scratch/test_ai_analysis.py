import cv2
import numpy as np
import os
import json

def create_sample_video(path, color_val, blur_kernel=None):
    fourcc = cv2.VideoWriter_fourcc(*'mp4v')
    out = cv2.VideoWriter(path, fourcc, 30, (640, 480))
    
    for i in range(30):
        # Create solid color frame or pattern
        img = np.full((480, 640, 3), color_val, dtype=np.uint8)
        # Add some text/shapes for sharpness testing
        cv2.putText(img, f"SIBI Test {i}", (50, 240), cv2.FONT_HERSHEY_SIMPLEX, 2, (255, 255, 255), 4)
        
        if blur_kernel:
            img = cv2.GaussianBlur(img, blur_kernel, 0)
            
        out.write(img)
    out.release()

# Create 3 distinct test videos: Dark, Normal, Bright
dark_path = 'storage/app/public/test_dark.mp4'
bright_path = 'storage/app/public/test_bright.mp4'
blurry_path = 'storage/app/public/test_blurry.mp4'

create_sample_video(dark_path, (15, 15, 15))      # Dark video (~30 brightness)
create_sample_video(bright_path, (220, 220, 220))  # Bright video (~230 brightness)
create_sample_video(blurry_path, (100, 100, 100), blur_kernel=(31, 31)) # Blurry video

print("Sample videos created successfully.")
