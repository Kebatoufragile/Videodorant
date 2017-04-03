#!/bin/bash

echo $1

cvlc v4l2:///dev/video0 --sout \'#transcode{vcodec=mp4v, acodec=mp4a, vb=1024}:rtp{sdp=rtsp://127.0.0.1:8554/$1}\'
#mplayer -fps 30 -tv driver=v4l2:width=640:height=480:device=/dev/video0 tv://
