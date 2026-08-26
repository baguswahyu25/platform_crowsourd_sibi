import cv2
import numpy as np
import subprocess
import json

# Create a sample motion video (640x480, 30fps) with moving object/text
path = 'storage/app/public/test_motion.mp4'
fourcc = cv2.VideoWriter_fourcc(*'mp4v')
out = cv2.VideoWriter(path, fourcc, 30, (640, 480))

for i in range(60): # 2 seconds of video
    # Create background frame (brightness around 100)
    img = np.full((480, 640, 3), (120, 120, 120), dtype=np.uint8)
    
    # Moving circle simulating hand gesture movement
    x_pos = 100 + (i * 7)
    y_pos = 200 + int(np.sin(i / 5.0) * 50)
    cv2.circle(img, (x_pos, y_pos), 40, (0, 200, 255), -1)
    
    # Text for sharpness (Laplacian variance)
    cv2.putText(img, f"SIBI Motion Frame {i}", (50, 400), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 0), 2)
    
    out.write(img)

out.release()
print("Motion video created at:", path)

# Run analyze_video.py on motion video
res = subprocess.check_output(['python', 'ai_validation/analyze_video.py', path])
print("Analysis Result:")
print(res.decode('utf-8'))
