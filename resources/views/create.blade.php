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
    z-index:50;
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
}
canvas{
    width:100%;
    max-width:420px;
    border-radius:18px;
    box-shadow:0 20px 40px rgba(0,0,0,.2);
    background:#fff;
}
.hint{
    font-size:13px;
    color:#777;
    margin-top:10px;
}
</style>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>

<body>

<header>
    <strong>PIXORA</strong>
    <a href="/frames">← Back to Frames</a>
</header>

<div class="container">

<div class="upload">
    <h3>Upload 6 Photos</h3>
    <p>Pilih 6 foto (kamera / galeri)</p>

    <div class="tools">
        <button onclick="openGallery()">📁 Galeri</button>
        <button onclick="openCamera()">📷 Kamera</button>
        <button onclick="capturePhoto()">📸 Ambil Foto</button>
    </div>

    <input type="file" id="photos" accept="image/*" multiple hidden>
    <video id="camera" autoplay playsinline muted></video>

    <div class="preview" id="preview"></div>

    <p class="hint">* Wajib 6 foto</p>

    <div class="tools">
        <button onclick="generate()">Generate Frame</button>
        <button class="download" onclick="download()">Download Foto</button>
    </div>
</div>

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

let photos = [];
let cameraStream = null;
let frameImgCache = null;

/* ===== LOAD IMAGE ===== */
async function loadImage(src){
    const img = new Image();
    img.src = src;
    await img.decode();
    return img;
}

/* ===== FRAME CACHE ===== */
async function ensureFrame(){
    if(frameImgCache) return frameImgCache;
    frameImgCache = await loadImage(frameSrc);
    canvas.width  = frameImgCache.naturalWidth;
    canvas.height = frameImgCache.naturalHeight;
    return frameImgCache;
}

/* ===== MASK SLOT DETECTOR (ADD ONLY) ===== */
function detectSlotsFromMask(maskImg){
    const t=document.createElement("canvas");
    t.width=maskImg.width;
    t.height=maskImg.height;
    const tc=t.getContext("2d");
    tc.drawImage(maskImg,0,0);

    const img=tc.getImageData(0,0,t.width,t.height);
    const data=img.data;
    const w=t.width,h=t.height;
    const visited=new Uint8Array(w*h);
    const slots=[];

    function isBlack(i){
        return data[i*4]<20 && data[i*4+1]<20 && data[i*4+2]<20;
    }

    function flood(start){
        let minX=w,minY=h,maxX=0,maxY=0;
        const stack=[start];
        visited[start]=1;
        while(stack.length){
            const i=stack.pop();
            const x=i%w,y=(i/w)|0;
            minX=Math.min(minX,x);
            minY=Math.min(minY,y);
            maxX=Math.max(maxX,x);
            maxY=Math.max(maxY,y);
            [[1,0],[-1,0],[0,1],[0,-1]].forEach(([dx,dy])=>{
                const nx=x+dx,ny=y+dy;
                if(nx<0||ny<0||nx>=w||ny>=h) return;
                const ni=ny*w+nx;
                if(!visited[ni] && isBlack(ni)){
                    visited[ni]=1;
                    stack.push(ni);
                }
            });
        }
        return {x:minX,y:minY,w:maxX-minX,h:maxY-minY};
    }

    for(let i=0;i<w*h;i++){
        if(!visited[i] && isBlack(i)){
            const box=flood(i);
            if(box.w>120 && box.h>160) slots.push(box);
        }
    }
    return slots.sort((a,b)=>a.y===b.y?a.x-b.x:a.y-b.y);
}

/* ===== GALERI ===== */
function openGallery(){ fileInput.click(); }
fileInput.addEventListener('change', async e=>{
    photos=[];
    for(const f of Array.from(e.target.files).slice(0,6)){
        photos.push(await loadImage(URL.createObjectURL(f)));
    }
    refreshPreview();
});

/* ===== KAMERA ===== */
async function openCamera(){
    cameraStream=await navigator.mediaDevices.getUserMedia({video:{facingMode:"user"},audio:false});
    video.srcObject=cameraStream;
    video.style.display='block';
}

/* ===== CAPTURE ===== */
function capturePhoto(){
    if(!video.srcObject) return alert("Kamera belum aktif");
    if(photos.length>=6) return alert("Maksimal 6 foto");
    const c=document.createElement('canvas');
    c.width=video.videoWidth;
    c.height=video.videoHeight;
    const cx=c.getContext('2d');
    cx.scale(-1,1);
    cx.drawImage(video,-c.width,0);
    c.toBlob(async b=>{
        photos.push(await loadImage(URL.createObjectURL(b)));
        refreshPreview();
    });
}

/* ===== PREVIEW ===== */
function refreshPreview(){
    preview.innerHTML='';
    photos.forEach(img=>{
        const im=document.createElement('img');
        im.src=img.src;
        preview.appendChild(im);
    });
}

/* ===== RENDER (MASK PRIORITY, FALLBACK AUTO) ===== */
async function render(frameImg){
    canvas.width=frameImg.naturalWidth;
    canvas.height=frameImg.naturalHeight;
    ctx.clearRect(0,0,canvas.width,canvas.height);

    let slotsFromMask=null;
    try{
        const maskSrc=frameSrc.replace(".png","_mask.png");
        const maskImg=await loadImage(maskSrc);
        slotsFromMask=detectSlotsFromMask(maskImg);
    }catch(e){}

    let slots=[];
    if(slotsFromMask && slotsFromMask.length){
        slots=slotsFromMask;
    }else{
        const temp=document.createElement("canvas");
        temp.width=canvas.width;
        temp.height=canvas.height;
        const tctx=temp.getContext("2d");
        tctx.drawImage(frameImg,0,0);
        const img=tctx.getImageData(0,0,temp.width,temp.height);
        const data=img.data,w=temp.width,h=temp.height;
        const visited=new Uint8Array(w*h);

        function isEmpty(i){
            const r=data[i*4],g=data[i*4+1],b=data[i*4+2],a=data[i*4+3];
            if(a<20) return true;
            const max=Math.max(r,g,b),min=Math.min(r,g,b);
            return (r+g+b)/3>200 && (max-min)<25;
        }

        function flood(start){
            let minX=w,minY=h,maxX=0,maxY=0;
            const stack=[start];
            visited[start]=1;
            while(stack.length){
                const i=stack.pop(),x=i%w,y=(i/w)|0;
                minX=Math.min(minX,x);
                minY=Math.min(minY,y);
                maxX=Math.max(maxX,x);
                maxY=Math.max(maxY,y);
                [[1,0],[-1,0],[0,1],[0,-1]].forEach(([dx,dy])=>{
                    const nx=x+dx,ny=y+dy;
                    if(nx<0||ny<0||nx>=w||ny>=h) return;
                    const ni=ny*w+nx;
                    if(!visited[ni] && isEmpty(ni)){
                        visited[ni]=1;
                        stack.push(ni);
                    }
                });
            }
            return {x:minX,y:minY,w:maxX-minX,h:maxY-minY};
        }

        for(let i=0;i<w*h;i++){
            if(!visited[i] && isEmpty(i)){
                const box=flood(i);
                if(box.w>120 && box.h>160) slots.push(box);
            }
        }
        slots.sort((a,b)=>a.y===b.y?a.x-b.x:a.y-b.y);
    }

    slots.slice(0,photos.length).forEach((slot,i)=>{
        const img=photos[i];
        const rImg=img.width/img.height;
        const rBox=slot.w/slot.h;
        let dw,dh;
        if(rImg>rBox){ dh=slot.h; dw=dh*rImg; }
        else{ dw=slot.w; dh=dw/rImg; }
        const dx=slot.x+(slot.w-dw)/2;
        const dy=slot.y+(slot.h-dh)/2;

        ctx.save();
        ctx.beginPath();
        ctx.rect(slot.x,slot.y,slot.w,slot.h);
        ctx.clip();
        ctx.drawImage(img,dx,dy,dw,dh);
        ctx.restore();
    });

    ctx.drawImage(frameImg,0,0,canvas.width,canvas.height);
}

/* ===== GENERATE ===== */
async function generate(){
    if(photos.length!==6) return alert("Harus 6 foto");
    const frameImg=await ensureFrame();
    await render(frameImg);
}
</script>

</body>
</html>
