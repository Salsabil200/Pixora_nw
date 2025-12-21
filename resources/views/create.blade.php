<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PIXORA – Create</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:#fff7fb;
}
header{
    padding:18px 24px;
    background:white;
    box-shadow:0 6px 20px rgba(0,0,0,.08);
    display:flex;
    justify-content:space-between;
}
header a{
    text-decoration:none;
    color:#111;
    font-weight:600;
}
.container{
    max-width:1200px;
    margin:auto;
    padding:30px;
    display:grid;
    grid-template-columns:420px 1fr;
    gap:30px;
}
.upload{
    background:white;
    padding:20px;
    border-radius:18px;
    box-shadow:0 10px 24px rgba(0,0,0,.08);
    position:relative;
    z-index:50; /* ✅ FIX: panel kiri selalu bisa diklik */
}
.tools{
    display:flex;
    gap:10px;
    margin:15px 0;
    flex-wrap:wrap;
}
button{
    padding:12px 22px;
    border:none;
    border-radius:999px;
    font-weight:800;
    background:#ec4899;
    color:white;
    cursor:pointer;
}
button:hover{ opacity:.9; }
button:disabled{
    opacity:.55;
    cursor:not-allowed;
}
.download{ background:#111; }

.preview{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:10px;
    margin-top:15px;
}
.preview img{
    width:100%;
    height:90px;
    object-fit:cover;
    border-radius:10px;
}
video{
    width:100%;
    border-radius:14px;
    margin-top:10px;
    display:none;
}
.canvas-wrap{
    display:flex;
    justify-content:center;
    align-items:flex-start;
    position:relative;
    z-index:1;
}
canvas{
    width:100%;
    max-width:420px;
    border-radius:18px;
    box-shadow:0 20px 40px rgba(0,0,0,.2);
    background:#fff;
    pointer-events:auto; /* ✅ harus bisa drag */
}

.hint{
    font-size:13px;color:#777;margin-top:10px
}

/* popup anim */
@keyframes pop {
  from { transform: translateY(8px) scale(.98); opacity:.6; }
  to   { transform: translateY(0) scale(1); opacity:1; }
}
</style>

<!-- MIDTRANS SNAP SANDBOX -->
<script
  src="https://app.sandbox.midtrans.com/snap/snap.js"
  data-client-key="{{ config('midtrans.client_key') }}">
</script>
</head>

<body>

<!-- ✅ SINGLE PREMIUM MODAL (HARUS DI DALAM BODY) -->
<div id="premiumModal" style="
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.45);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
">
    <div style="
        background:white;
        width:360px;
        border-radius:22px;
        padding:26px;
        text-align:center;
        box-shadow:0 20px 50px rgba(0,0,0,.25);
        animation:pop .25s ease;
    ">
        <h3 style="margin:0 0 8px">Upgrade ke Premium ✨</h3>
        <p style="color:#777;font-size:14px">
            Download video adalah fitur <b>Premium</b>
        </p>

        <div style="font-size:26px;font-weight:900;color:#ec4899;margin:14px 0">
            Rp 9.000
        </div>

        <div style="display:flex;gap:10px;margin-top:20px">
            <button type="button" onclick="payPremium()" style="
                flex:1;
                background:#ec4899;
                color:white;
                border:none;
                padding:12px;
                border-radius:999px;
                font-weight:800;
                cursor:pointer;
            ">Upgrade</button>

            <button type="button" onclick="closePremium()" style="
                flex:1;
                background:#e5e7eb;
                color:#111;
                border:none;
                padding:12px;
                border-radius:999px;
                font-weight:800;
                cursor:pointer;
            ">Nanti</button>
        </div>
    </div>
</div>

<header>
    <strong>PIXORA</strong>
    <a href="/frames">← Back to Frames</a>
</header>

<div class="container">

<!-- LEFT -->
<div class="upload">
    <div class="tools">
        <button type="button" onclick="prevPhoto()">⬅</button>

        <input type="range"
               id="zoomSlider"
               min="0.5"
               max="2.5"
               step="0.01"
               value="1"
               oninput="zoomActive(this.value)">

        <button type="button" onclick="nextPhoto()">➡</button>
    </div>

    <p class="hint">
        Geser foto di canvas & atur zoom sebelum Generate
    </p>

    <h3>Upload 6 Photos</h3>
    <p>Pilih 6 foto (kamera / galeri)</p>

    <div class="tools">
        <button type="button" onclick="openGallery()">📁 Galeri</button>
        <button type="button" onclick="openCamera()">📷 Kamera</button>
        <button type="button" onclick="capturePhoto()">📸 Ambil Foto</button>
    </div>

    <input type="file" id="photos" accept="image/*" multiple hidden>
    <video id="camera" autoplay playsinline muted></video>

    <div class="preview" id="preview"></div>

    <p class="hint">
        * Wajib 6 foto
    </p>

    <div class="tools">
        <button id="btnGenerate" type="button" onclick="generate()">Generate Frame</button>
        <button type="button" class="download" onclick="download()">Download Foto</button>
        <button type="button" class="download" id="btnVideo" onclick="downloadVideo()">Download Video</button>
    </div>
</div>

<!-- RIGHT -->
<div class="canvas-wrap">
    <canvas id="canvas"></canvas>
</div>

</div>

<script>
const frameSrc = "/{{ $frame }}";
const fileInput = document.getElementById('photos');
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
const preview = document.getElementById('preview');
const video = document.getElementById('camera');
const btnVideo = document.getElementById('btnVideo');

let photos = []; // { img, x, y, scale }
let activeIndex = 0;
let dragging = false;
let lastX = 0;
let lastY = 0;
let cameraStream = null;
let frameImgCache = null;

/* ===== SLOT 2x3 (ukuran frame ikut file frame asli, seperti awal) ===== */
function buildSlots(w,h){
    const px = w * 0.12;
    const py = h * 0.08;
    const gx = w * 0.06;
    const gy = h * 0.05;
    const pw = (w - px*2 - gx) / 2;
    const ph = (h - py*2 - gy*2) / 3;

    return [
        {x:px, y:py, w:pw, h:ph},
        {x:px+pw+gx, y:py, w:pw, h:ph},
        {x:px, y:py+ph+gy, w:pw, h:ph},
        {x:px+pw+gx, y:py+ph+gy, w:pw, h:ph},
        {x:px, y:py+(ph+gy)*2, w:pw, h:ph},
        {x:px+pw+gx, y:py+(ph+gy)*2, w:pw, h:ph},
    ];
}

/* ===== helpers ===== */
function sleep(ms){ return new Promise(r => setTimeout(r, ms)); }
async function loadImage(src){
    const img = new Image();
    img.src = src;
    await img.decode();
    return img;
}

/* ===== init frame cache (biar redraw cepat & konsisten) ===== */
async function ensureFrame(){
    if(frameImgCache) return frameImgCache;
    frameImgCache = await loadImage(frameSrc);
    // canvas ikut ukuran frame asli (seperti awal)
    canvas.width = frameImgCache.naturalWidth;
    canvas.height = frameImgCache.naturalHeight;
    return frameImgCache;
}

/* ===== FIX: tombol galeri ===== */
function openGallery(){
    fileInput.click();
}

/* ===== GALERI LOAD ===== */
fileInput.addEventListener('change', async (e)=>{
    const files = Array.from(e.target.files).slice(0,6);
    photos = [];

    for(const f of files){
        const img = await loadImage(URL.createObjectURL(f));
        photos.push({ img, x:0, y:0, scale:1 });
    }
    activeIndex = 0;
    document.getElementById('zoomSlider').value = photos[0] ? photos[0].scale : 1;
    refreshPreview();
    await redraw(); // langsung render preview ke canvas
});

/* ===== KAMERA ===== */
async function openCamera(){
    try{
        cameraStream = await navigator.mediaDevices.getUserMedia({
            video:{ facingMode:"user" },
            audio:false
        });
        video.srcObject = cameraStream;
        video.style.display = 'block';
    }catch{
        alert("Kamera tidak tersedia / izin ditolak");
    }
}

/* ===== CAPTURE PHOTO ===== */
function capturePhoto(){
    if(!video.srcObject) return alert("Kamera belum aktif");
    if(photos.length >= 6) return alert("Maksimal 6 foto");

    const c=document.createElement('canvas');
    c.width=video.videoWidth;
    c.height=video.videoHeight;

    const cx=c.getContext('2d');
    // mirror selfie
    cx.scale(-1,1);
    cx.drawImage(video,-c.width,0);

    c.toBlob(async (b)=>{
        const img = await loadImage(URL.createObjectURL(b));
        photos.push({ img, x:0, y:0, scale:1 });
        if(photos.length === 1){
            activeIndex = 0;
            document.getElementById('zoomSlider').value = 1;
        }
        refreshPreview();
        await redraw();
    }, "image/png");
}

/* ===== PREVIEW ===== */
function refreshPreview(){
    preview.innerHTML='';
    photos.forEach((p,i)=>{
        const im=document.createElement('img');
        im.src=p.img.src;
        im.style.outline = i===activeIndex ? "3px solid #ec4899" : "none";
        im.onclick=async ()=>{
            activeIndex=i;
            document.getElementById('zoomSlider').value = photos[i].scale;
            refreshPreview();
            await redraw();
        };
        preview.appendChild(im);
    });
}

/* ===== RENDER CANVAS (pakai state drag/zoom) ===== */
async function render(frameImg, k){
    // pastikan ukuran canvas ikut frame asli
    canvas.width = frameImg.naturalWidth;
    canvas.height = frameImg.naturalHeight;

    ctx.clearRect(0,0,canvas.width,canvas.height);
    const slots = buildSlots(canvas.width, canvas.height);

    for(let i=0; i<k; i++){
        const p = photos[i];
        const s = slots[i];

        // crop "cover" manual pakai clip + transform (biar drag/zoom enak)
        ctx.save();
        ctx.beginPath();
        ctx.rect(s.x, s.y, s.w, s.h);
        ctx.clip();

        const cx = s.x + s.w/2 + p.x;
        const cy = s.y + s.h/2 + p.y;

        ctx.translate(cx, cy);
        ctx.scale(p.scale, p.scale);

        // gambar foto, besar minimal menutupi slot (cover)
        const img = p.img;
        const scaleCover = Math.max(s.w / img.width, s.h / img.height);
        const dw = img.width * scaleCover;
        const dh = img.height * scaleCover;

        ctx.drawImage(img, -dw/2, -dh/2, dw, dh);
        ctx.restore();
    }

    // frame overlay
    ctx.drawImage(frameImg,0,0,canvas.width,canvas.height);
}

/* ===== REDRAW PREVIEW di canvas (tanpa "Generate" pun kelihatan) ===== */
async function redraw(){
    if(photos.length === 0) return;
    const frameImg = await ensureFrame();
    await render(frameImg, photos.length);
}

/* ===== GENERATE FINAL (6 foto wajib) ===== */
async function generate(){
    if(photos.length !== 6){
        alert("Harus 6 foto");
        return;
    }
    const frameImg = await ensureFrame();
    await render(frameImg, 6);
}

/* ===== DOWNLOAD FOTO ===== */
function download(){
    if(photos.length === 0) return alert("Upload / ambil foto dulu");
    const a=document.createElement('a');
    a.download="pixora.png";
    a.href=canvas.toDataURL("image/png");
    a.click();
}

/* ===== DRAG di CANVAS (mouse + touch) ===== */
function getCanvasPos(evt){
    const rect = canvas.getBoundingClientRect();
    const clientX = evt.touches ? evt.touches[0].clientX : evt.clientX;
    const clientY = evt.touches ? evt.touches[0].clientY : evt.clientY;
    // konversi ke koordinat canvas internal
    const x = (clientX - rect.left) * (canvas.width / rect.width);
    const y = (clientY - rect.top) * (canvas.height / rect.height);
    return {x,y};
}

canvas.addEventListener('mousedown', (e)=>{
    if(!photos[activeIndex]) return;
    dragging = true;
    const p = getCanvasPos(e);
    lastX = p.x; lastY = p.y;
});
window.addEventListener('mousemove', async (e)=>{
    if(!dragging || !photos[activeIndex]) return;
    const p = getCanvasPos(e);
    photos[activeIndex].x += (p.x - lastX);
    photos[activeIndex].y += (p.y - lastY);
    lastX = p.x; lastY = p.y;
    await redraw();
});
window.addEventListener('mouseup', ()=> dragging=false);

canvas.addEventListener('touchstart', (e)=>{
    if(!photos[activeIndex]) return;
    dragging = true;
    const p = getCanvasPos(e);
    lastX = p.x; lastY = p.y;
}, {passive:true});

canvas.addEventListener('touchmove', async (e)=>{
    if(!dragging || !photos[activeIndex]) return;
    const p = getCanvasPos(e);
    photos[activeIndex].x += (p.x - lastX);
    photos[activeIndex].y += (p.y - lastY);
    lastX = p.x; lastY = p.y;
    await redraw();
}, {passive:true});

canvas.addEventListener('touchend', ()=> dragging=false);
canvas.addEventListener('touchcancel', ()=> dragging=false);

/* ===== ZOOM ===== */
async function zoomActive(val){
    if(!photos[activeIndex]) return;
    photos[activeIndex].scale = parseFloat(val);
    await redraw();
}

/* ===== NAV FOTO AKTIF ===== */
async function prevPhoto(){
    if(activeIndex>0){
        activeIndex--;
        document.getElementById('zoomSlider').value = photos[activeIndex].scale;
        refreshPreview();
        await redraw();
    }
}
async function nextPhoto(){
    if(activeIndex < photos.length-1){
        activeIndex++;
        document.getElementById('zoomSlider').value = photos[activeIndex].scale;
        refreshPreview();
        await redraw();
    }
}

/* ===== DOWNLOAD VIDEO (pakai state photos, bukan images) ===== */
async function downloadVideo(){
    if(photos.length !== 6) return alert("Harus 6 foto dulu");

    btnVideo.disabled = true;
    btnVideo.textContent = "Rendering video...";

    try{
        const frameImg = await ensureFrame();

        // Pastikan canvas sudah ukuran frame
        canvas.width = frameImg.naturalWidth;
        canvas.height = frameImg.naturalHeight;

        const stream = canvas.captureStream(30);
        const chunks = [];

        // fallback mimeType aman
        let mimeType = "video/webm;codecs=vp9";
        if(!MediaRecorder.isTypeSupported(mimeType)){
            mimeType = "video/webm;codecs=vp8";
        }
        if(!MediaRecorder.isTypeSupported(mimeType)){
            mimeType = "video/webm";
        }

        const rec = new MediaRecorder(stream, { mimeType });

        rec.ondataavailable = e => { if(e.data.size>0) chunks.push(e.data); };
        const stopped = new Promise(resolve => { rec.onstop = () => resolve(); });

        let currentStep = 0;
        const drawTimer = setInterval(async () => {
            await render(frameImg, currentStep);
        }, 1000/30);

        rec.start();

        currentStep = 0; await sleep(350);
        for(let k=1; k<=6; k++){
            currentStep = k;
            await sleep(650);
        }
        await sleep(850);

        clearInterval(drawTimer);
        rec.stop();
        await stopped;

        const blob = new Blob(chunks, { type:"video/webm" });
        const a = document.createElement("a");
        a.href = URL.createObjectURL(blob);
        a.download = "pixora-montage.webm";
        a.click();

    }catch(err){
        console.error(err);
        alert("Gagal membuat video. Coba di Chrome/Edge ya.");
    }finally{
        btnVideo.disabled = false;
        btnVideo.textContent = "Download Video";
    }
}
</script>

<!-- ✅ PREMIUM GATE + MIDTRANS (ADD ONLY, TIDAK NYENTUH KODE UTAMA) -->
<script>
let isPremiumUser = false;

// simpan referensi fungsi downloadVideo yang asli
const originalDownloadVideo = window.downloadVideo;

document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("btnVideo");
  if(!btn) return;

  // tangkap klik sebelum onclick inline jalan
  btn.addEventListener("click", (e) => {
    if(!isPremiumUser){
      e.preventDefault();
      e.stopImmediatePropagation();
      openPremium();
    }
  }, true);
});

function openPremium(){
  const modal = document.getElementById("premiumModal");
  if(modal) modal.style.display = "flex";
}
function closePremium(){
  const modal = document.getElementById("premiumModal");
  if(modal) modal.style.display = "none";
}

async function payPremium(){
  try{
    if(typeof snap === "undefined"){
      alert("Snap Midtrans belum kebaca. Cek client key / koneksi.");
      return;
    }

    const res = await fetch("/pay/premium", {
      method: "POST",
      headers:{
        "X-CSRF-TOKEN": "{{ csrf_token() }}",
        "Accept": "application/json"
      }
    });

    const data = await res.json();
    if(!data.token){
      alert("Token Midtrans kosong. Cek route / controller / server key.");
      return;
    }

    snap.pay(data.token, {
      onSuccess: function(){
        isPremiumUser = true;
        closePremium();
        alert("Pembayaran berhasil 🎉 Premium aktif");
        originalDownloadVideo();
      },
      onPending: function(){ alert("Menunggu pembayaran..."); },
      onError: function(){ alert("Pembayaran gagal"); },
      onClose: function(){}
    });

  }catch(err){
    console.error(err);
    alert("Gagal memanggil payment. Cek route /pay/premium");
  }
}
</script>

</body>
</html>
