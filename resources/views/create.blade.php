<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PIXORA – Studio Creative</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <style>
        .btn-reset {
            background-color: #ef4444 !important;
            color: white !important;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-reset:hover { background-color: #dc2626 !important; }

        .preview {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin: 20px 0;
            padding: 0;
        }

        .preview-item {
            position: relative;
            cursor: pointer;
            width: 100%;
            aspect-ratio: 1 / 1;
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #ec4899;
            display: block;
        }

        .preview-item:hover::after {
            content: "✕";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(239, 68, 68, 0.8);
            color: white;
            font-size: 22px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: 0.2s;
        }
    </style>
</head>

<body>

<header>
    <a href="{{ route('frames.index') }}" class="logo-wrap" style="text-decoration: none;">
        <svg width="120" height="35" viewBox="0 0 120 40" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="pixoraGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" style="stop-color:#ec4899;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" />
                </linearGradient>
            </defs>
            <text x="0" y="30" font-family="'Plus Jakarta Sans', sans-serif" font-weight="800" font-size="28" fill="url(#pixoraGrad)">
                PIXORA
            </text>
        </svg>
    </a>
    <a href="{{ route('frames.index') }}" style="text-decoration: none; font-weight: 700; color: #4b5563; font-family: sans-serif;">← Back to Frames</a>
</header>

<div class="container">
    <div class="upload">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h3 style="margin: 0; font-family: sans-serif;">✨ Studio Creative</h3>
            <button onclick="resetAll()" class="btn-reset">Reset Semua</button>
        </div>
        <p style="font-size: 14px; color: #6b7280; margin-bottom: 20px; font-family: sans-serif;">Maksimal 6 foto. Klik foto untuk hapus.</p>

        <div class="tools">
            <button onclick="openGallery()">📁 Galeri</button>
            <button onclick="openCamera()">📷 Kamera</button>
            <button class="btn-snap" onclick="capturePhoto()">📸 Snap!</button>
        </div>

        <input type="file" id="photos" accept="image/*" multiple hidden>
        
        <video id="camera" autoplay playsinline muted style="display:none; width: 100%; border-radius: 14px; margin-bottom: 15px; border: 2px solid #ec4899; background: #000;"></video>

        <div class="preview" id="preview"></div>

        <div class="tools">
            <button class="btn-primary" style="flex: 1;" onclick="generatePhoto()">Generate Foto</button>
            <button class="btn-secondary" style="flex: 1;" onclick="payAndGenerateVideo()">Generate Video (Premium)</button>
        </div>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
        
        <div class="tools">
            <button class="download" onclick="downloadImage()">💾 Download Foto (PNG)</button>
            <button id="btnVideo" class="download" style="background-color: #4f46e5;" onclick="downloadVideo()">🎥 Download Video (WebM)</button>
        </div>
    </div>

    <div class="canvas-wrap">
        <canvas id="canvas"></canvas>
    </div>
</div>

<script>
    const frameSrc = "{{ asset($frame) }}"; 
    const fileInput = document.getElementById('photos');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d', { alpha: true });
    const preview = document.getElementById('preview');
    const video = document.getElementById('camera');

    let photos = [];
    let cameraStream = null;
    let frameImgCache = null;
    let slotsCache = []; 
    let animationId = null; 
    let isAnimating = false;
    let hasPaid = false; // Status Pembayaran

    async function loadImage(src){
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.crossOrigin = "anonymous";
            img.onload = () => resolve(img);
            img.onerror = (e) => reject(e);
            img.src = src;
        });
    }

    async function ensureFrame(){
        if(frameImgCache) return frameImgCache;
        try {
            frameImgCache = await loadImage(frameSrc);
            canvas.width  = frameImgCache.naturalWidth;
            canvas.height = frameImgCache.naturalHeight;
            slotsCache = await detectSlots(frameImgCache);
            return frameImgCache;
        } catch (e) {
            console.error("Gagal memuat frame:", frameSrc);
        }
    }

    async function detectSlots(frameImg) {
        const temp = document.createElement("canvas");
        temp.width = canvas.width; temp.height = canvas.height;
        const tctx = temp.getContext("2d");
        tctx.drawImage(frameImg, 0, 0);
        const imgData = tctx.getImageData(0, 0, temp.width, temp.height);
        const data = imgData.data, w = temp.width, h = temp.height;
        const visited = new Uint8Array(w * h);
        const slots = [];

        function isEmpty(i) {
            const a = data[i * 4 + 3];
            if (a < 50) return true;
            const r = data[i * 4], g = data[i * 4 + 1], b = data[i * 4 + 2];
            return (r > 240 && g > 240 && b > 240);
        }

        for (let i = 0; i < w * h; i++) {
            if (!visited[i] && isEmpty(i)) {
                let minX = w, minY = h, maxX = 0, maxY = 0;
                const stack = [i];
                visited[i] = 1;
                while (stack.length) {
                    const idx = stack.pop(), x = idx % w, y = (idx / w) | 0;
                    minX = Math.min(minX, x); minY = Math.min(minY, y);
                    maxX = Math.max(maxX, x); maxY = Math.max(maxY, y);
                    [[1, 0], [-1, 0], [0, 1], [0, -1]].forEach(([dx, dy]) => {
                        const nx = x + dx, ny = y + dy;
                        const ni = ny * w + nx;
                        if (nx >= 0 && ny >= 0 && nx < w && ny < h && !visited[ni] && isEmpty(ni)) {
                            visited[ni] = 1; stack.push(ni);
                        }
                    });
                }
                const box = { x: minX, y: minY, w: maxX - minX, h: maxY - minY };
                if (box.w > 100 && box.h > 100) slots.push(box);
            }
        }
        return slots.sort((a, b) => a.y === b.y ? a.x - b.x : a.y - b.y);
    }

    async function initCanvas() {
        await ensureFrame();
        if(frameImgCache) {
            ctx.drawImage(frameImgCache, 0, 0, canvas.width, canvas.height);
        }
    }

    // FUNGSI PEMBAYARAN & GENERATE
    async function payAndGenerateVideo() {
        if (photos.length < 1) return alert("Pilih foto dulu!");
        
        if (hasPaid) {
            startVideoLogic();
            return;
        }

        try {
            const response = await fetch("{{ route('payment.tokenize') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            });
            const data = await response.json();

            window.snap.pay(data.token, {
                onSuccess: function(result) {
                    hasPaid = true;
                    alert("Pembayaran Berhasil! Video sedang diproses.");
                    startVideoLogic();
                    // Update status di DB lokal
                    fetch("/payment/update-local", {
                        method: "POST",
                        headers: {"Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}"},
                        body: JSON.stringify({order_id: result.order_id, status: 'settlement'})
                    });
                },
                onPending: function(result) { alert("Selesaikan pembayaran anda."); },
                onError: function(result) { alert("Pembayaran Gagal."); }
            });
        } catch (err) {
            alert("Sistem sibuk, coba lagi nanti.");
        }
    }

    function startVideoLogic() {
        isAnimating = true;
        if(animationId) cancelAnimationFrame(animationId);
        let lastSwitch = 0;
        let photoOffset = 0;
        const interval = 600;
        function loop(timestamp) {
            if (!lastSwitch) lastSwitch = timestamp;
            if (timestamp - lastSwitch > interval) {
                photoOffset++;
                lastSwitch = timestamp;
            }
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            slotsCache.forEach((slot, i) => {
                const imgIndex = (i + photoOffset) % photos.length;
                const img = photos[imgIndex];
                if (img) drawPhotoInSlot(img, slot);
            });
            ctx.drawImage(frameImgCache, 0, 0, canvas.width, canvas.height);
            animationId = requestAnimationFrame(loop);
        }
        animationId = requestAnimationFrame(loop);
    }

    function openGallery(){ fileInput.click(); }
    
    fileInput.addEventListener('change', async e=>{
        const files = Array.from(e.target.files);
        for(const f of files){
            if(photos.length < 6) {
                photos.push(await loadImage(URL.createObjectURL(f)));
            }
        }
        refreshPreview();
    });

    async function openCamera(){
        try {
            cameraStream = await navigator.mediaDevices.getUserMedia({video:{facingMode:"user"},audio:false});
            video.srcObject = cameraStream;
            video.style.display = 'block';
        } catch (err) {
            alert("Kamera tidak diizinkan atau tidak tersedia");
        }
    }

    function capturePhoto(){
        if(!video.srcObject) return alert("Kamera belum aktif");
        if(photos.length>=6) return alert("Maksimal 6 foto");
        const c = document.createElement('canvas');
        c.width = video.videoWidth; c.height = video.videoHeight;
        const cx = c.getContext('2d');
        cx.translate(c.width, 0); cx.scale(-1, 1);
        cx.drawImage(video, 0, 0);
        loadImage(c.toDataURL('image/png')).then(img => {
            photos.push(img);
            refreshPreview();
        });
    }

    function deletePhoto(index) {
        photos.splice(index, 1);
        refreshPreview();
    }

    function resetAll() {
        if(confirm("Hapus semua foto?")) {
            photos = [];
            isAnimating = false;
            hasPaid = false; // Reset status bayar
            if(animationId) cancelAnimationFrame(animationId);
            ctx.clearRect(0,0,canvas.width,canvas.height);
            ctx.drawImage(frameImgCache, 0, 0, canvas.width, canvas.height);
            refreshPreview();
        }
    }

    function refreshPreview(){
        preview.innerHTML='';
        photos.forEach((img, index)=>{
            const div = document.createElement('div');
            div.className = 'preview-item';
            div.onclick = () => deletePhoto(index);
            const im = document.createElement('img');
            im.src = img.src;
            div.appendChild(im);
            preview.appendChild(div);
        });
    }

    function drawPhotoInSlot(img, slot) {
        const rImg = img.width / img.height;
        const rBox = slot.w / slot.h;
        let dw, dh, dx, dy;
        if(rImg > rBox){ dh=slot.h; dw=dh*rImg; }
        else{ dw=slot.w; dh=dw/rImg; }
        dx = slot.x + (slot.w - dw) / 2;
        dy = slot.y + (slot.h - dh) / 2;
        ctx.save();
        ctx.beginPath();
        ctx.rect(slot.x, slot.y, slot.w, slot.h);
        ctx.clip();
        ctx.drawImage(img, dx, dy, dw, dh);
        ctx.restore();
    }

    function generatePhoto(){
        if(photos.length < 1) return alert("Pilih foto dulu!");
        isAnimating = false;
        if(animationId) cancelAnimationFrame(animationId);
        ensureFrame().then(frameImg => {
            ctx.clearRect(0,0,canvas.width,canvas.height);
            slotsCache.forEach((slot, i) => {
                if(photos[i]) drawPhotoInSlot(photos[i], slot);
            });
            ctx.drawImage(frameImg, 0, 0, canvas.width, canvas.height);
        });
    }

    function downloadImage(){
        const link = document.createElement('a');
        link.download = 'pixora-' + Date.now() + '.png';
        link.href = canvas.toDataURL('image/png', 1.0);
        link.click();
    }

    async function downloadVideo(){
        if(!hasPaid) return alert("Fitur Video adalah Premium. Silahkan klik 'Generate Video' untuk membayar.");
        if(!isAnimating) return alert("Video belum di-generate.");
        
        const btnVideo = document.getElementById("btnVideo");
        btnVideo.disabled = true;
        btnVideo.textContent = "⏱️ Memproses...";
        try {
            const stream = canvas.captureStream(60); 
            const recorder = new MediaRecorder(stream, { 
                mimeType: 'video/webm;codecs=vp9',
                videoBitsPerSecond: 8000000 
            });
            const chunks = [];
            recorder.ondataavailable = e => { if(e.data.size > 0) chunks.push(e.data); };
            recorder.onstop = () => {
                const blob = new Blob(chunks, { type: 'video/webm' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'pixora-video-' + Date.now() + '.webm';
                a.click();
                btnVideo.disabled = false;
                btnVideo.textContent = "🎥 Download Video (WebM)";
            };
            recorder.start();
            setTimeout(() => recorder.stop(), 4000); 
        } catch (e) {
            alert("Gagal memproses video");
            btnVideo.disabled = false;
        }
    }

    window.onload = initCanvas;
</script>

</body>
</html>