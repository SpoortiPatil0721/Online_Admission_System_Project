<!--<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Document Upload</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
    }

    header {
      background-color: #ea6a2e;
      color: white;
      padding: 10px;
      text-align: center;
    }

    .container {
      padding: 20px;
      background-color: white;
      max-width: 100%;
      margin: 0 auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .nav {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 30px;
      margin-bottom: 20px;
      border-bottom: 1px solid #ccc;
      padding-bottom: 10px;
    }

    .step {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #666;
      font-weight: 500;
    }

    .step.completed .circle {
      background-color: green;
      border-color: green;
    }

    .step.active {
      color: #ea6a2e;
      font-weight: bold;
    }

    .circle {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background-color: transparent;
      border: 2px solid #ccc;
    }

    .step.active .circle {
      background-color: #ea6a2e;
      border-color: #ea6a2e;
    }

    h2 {
      color: #c33;
    }

    .upload-section {
      margin-top: 20px;
    }

    .upload-instructions {
      margin-bottom: 15px;
      font-size: 0.95em;
      line-height: 1.6;
      color: #333;
    }

    .upload-box {
      display: flex;
      gap: 30px;
      margin-top: 20px;
      flex-wrap: wrap;
    }

    .upload-field {
      flex: 1;
    }

    .upload-field label {
      font-weight: bold;
      display: block;
      margin-bottom: 8px;
    }

    .upload-button {
      background-color: #ea6a2e;
      border: none;
      color: white;
      padding: 10px 15px;
      cursor: pointer;
    }

    .upload-button:hover {
      background-color: #c2541c;
    }

    .file-info {
      margin-top: 5px;
      color: #3366cc;
      font-size: 0.95em;
    }

    .note {
      margin-top: 30px;
      font-size: 0.9em;
      color: #a00;
    }

    .buttons {
      display: flex;
      justify-content: flex-end;
      gap: 15px;
      margin-top: 40px;
    }

    .btn {
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
      border: none;
      color: white;
      background-color: #ea6a2e;
    }

    .btn:hover {
      background-color: #c2541c;
    }
    .required {
  color: red;
}

  </style>
</head>
<body>

<header>
  <h1>KLE Society's<br><small>College of Computer Application, Dharwad</small></h1>
</header>

<div class="container">

  <-- Navigation --
  <div class="nav">
    <div class="step completed"><div class="circle"></div> Important Instructions</div>
    <div class="step completed"><div class="circle"></div> Category Details</div>
    <div class="step completed"><div class="circle"></div> Program Details</div>
    <div class="step active"><div class="circle"></div> Personal Details</div>
    <div class="step"><div class="circle"></div> Guardian and Address Details</div>
    <div class="step"><div class="circle"></div> Declaration</div>
    <div class="step"><div class="circle"></div> Payments</div>
  </div>

  <!-- Upload Section --
  <div class="upload-section">
    <h2>📄 Document Upload</h2>

    <div class="upload-instructions">
      <strong>Photograph:</strong><br/>
      Recent Passport size photograph (taken in last 6 months) with light background (preferably white).<br/>
      Selfies/Photos cropped from group photographs or poor-resolution images will be rejected.<br/><br/>
      <strong>Signature:</strong><br/>
      Must be signed by the candidate on a white paper using a black/blue pen.<br/>
      Only the signature area should be uploaded. Do NOT upload the full page.
    </div>

    
    <div class="upload-box">
      <!-- Photo Upload --
      <div class="upload-field">
        <label><span class="required">*</span> Passport Size Photo</label>
        <input type="file" accept=".jpg,.jpeg" onchange="handleFileUpload(this, 'photo-info', 'photo-preview', 'photo-upload')" required />
        <button type="button" class="upload-button" id="photo-upload" disabled onclick="uploadFile('photo')">Upload File</button>
        <div class="file-info" id="photo-info" style="display: none;"></div>
        <img id="photo-preview" style="max-width: 120px; display: none; margin-top: 10px;" />
        <div style="font-size: 0.85em; color: #555;">Max 2 MB, .jpg/.jpeg only</div>
      </div>
    
      <!-- Signature Upload --
      <div class="upload-field">
        <label><span class="required">*</span> Signature</label>
        <input type="file" accept=".jpg,.jpeg" onchange="handleFileUpload(this, 'signature-info', 'signature-preview', 'signature-upload')" required />
        <button type="button" class="upload-button" id="signature-upload" disabled onclick="uploadFile('signature')">Upload File</button>
        <div class="file-info" id="signature-info" style="display: none;"></div>
        <img id="signature-preview" style="max-width: 120px; display: none; margin-top: 10px;" />
        <div style="font-size: 0.85em; color: #555;">Max 2 MB, .jpg/.jpeg only</div>
      </div>
    
      <!-- Aadhar Upload --
      <div class="upload-field">
        <label><span class="required">*</span> Government ID Proof (Aadhar Card)</label>
        <input type="file" accept=".pdf" onchange="handleFileUpload(this, 'aadhar-info', null, 'aadhar-upload')" required />
        <button type="button" class="upload-button" id="aadhar-upload" disabled onclick="uploadFile('aadhar')">Upload File</button>
        <div class="file-info" id="aadhar-info" style="display: none;"></div>
        <div style="font-size: 0.85em; color: #555;">Max 2 MB, PDF only</div>
      </div>
    </div>
  
    <div class="note">Wrong input or document would be treated as invalid application.</div>

    <!-- Buttons --
    <div class="buttons">
      <button class="btn" onclick="window.history.back()">Previous</button>
      <button class="btn" onclick="goToNext('GandA-details.html')">Next</button>
    </div>
  </div>

</div>

<script>
  function goToNext(page) {
    window.location.href = page;
  }



  let uploadedFiles = {
    photo: false,
    signature: false,
    aadhar: false
  };

  function uploadFile(type) {
    const input = document.querySelector(`input[type="file"][accept$=${type === 'aadhar' ? '.pdf' : '.jpeg'}]`);
    const file = input.files[0];

    if (!file) return;

    const formData = new FormData();
    formData.append('file', file);
    formData.append('type', type);

    fetch('upload-handler.php', {
      method: 'POST',
      body: formData
    }).then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          alert(type.charAt(0).toUpperCase() + type.slice(1) + ' uploaded successfully!');
          uploadedFiles[type] = true;
          checkAllUploaded();
        } else {
          alert('Upload failed: ' + data.message);
        }
      }).catch(err => {
        alert('Upload error.');
      });
  }

  function checkAllUploaded() {
    const nextBtn = document.querySelector('.buttons button:last-child');
    nextBtn.disabled = !(uploadedFiles.photo && uploadedFiles.signature && uploadedFiles.aadhar);
  }

  function handleFileUpload(input, infoId, previewId, buttonId) {
    const file = input.files[0];
    const fileInfo = document.getElementById(infoId);
    const uploadButton = document.getElementById(buttonId);

    if (file) {
      fileInfo.textContent = file.name;
      fileInfo.style.display = 'block';
      uploadButton.disabled = false;

      if (previewId && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (e) {
          const img = document.getElementById(previewId);
          img.src = e.target.result;
          img.style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    } else {
      fileInfo.style.display = 'none';
      uploadButton.disabled = true;

      if (previewId) {
        document.getElementById(previewId).style.display = 'none';
      }
    }
  }

  function goToNext(page) {
    if (uploadedFiles.photo && uploadedFiles.signature && uploadedFiles.aadhar) {
      window.location.href = page;
    } else {
      alert("Please upload all required documents before proceeding.");
    }
  }

  // Disable "Next" initially
  window.onload = () => {
    checkAllUploaded();
  };


</script>

</body>
</html>
-->

<?php
session_start();
$application_no = $_SESSION['application_no'] ?? null;

$photoPath = $signaturePath = $aadharPath = '';

if ($application_no) {
    $conn = new mysqli("localhost", "root", "", "kle_users");
    if (!$conn->connect_error) {
        $stmt = $conn->prepare("SELECT photo_path, signature_path, aadhar_path FROM uploaded_documents WHERE application_no = ?");
        $stmt->bind_param("s", $application_no);
        $stmt->execute();
        $stmt->bind_result($photoPath, $signaturePath, $aadharPath);
        $stmt->fetch();
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Document Upload</title>
  <style>
    /* Your full CSS remains unchanged */
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
    }
    header {
      background-color: #ea6a2e;
      color: white;
      padding: 10px;
      text-align: center;
    }
    .container {
      padding: 20px;
      background-color: white;
      max-width: 100%;
      margin: 0 auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .nav {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 30px;
      margin-bottom: 20px;
      border-bottom: 1px solid #ccc;
      padding-bottom: 10px;
    }
    .step {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #666;
      font-weight: 500;
    }
    .step.completed .circle {
      background-color: green;
      border-color: green;
    }
    .step.active {
      color: #ea6a2e;
      font-weight: bold;
    }
    .circle {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background-color: transparent;
      border: 2px solid #ccc;
    }
    .step.active .circle {
      background-color: #ea6a2e;
      border-color: #ea6a2e;
    }
    h2 { color: #c33; }
    .upload-section { margin-top: 20px; }
    .upload-instructions {
      margin-bottom: 15px;
      font-size: 0.95em;
      line-height: 1.6;
      color: #333;
    }
    .upload-box {
      display: flex;
      gap: 30px;
      margin-top: 20px;
      flex-wrap: wrap;
    }
    .upload-field {
      flex: 1;
    }
    .upload-field label {
      font-weight: bold;
      display: block;
      margin-bottom: 8px;
    }
    .upload-button {
      background-color: #ea6a2e;
      border: none;
      color: white;
      padding: 10px 15px;
      cursor: pointer;
    }
    .upload-button:hover { background-color: #c2541c; }
    .file-info { margin-top: 5px; color: #3366cc; font-size: 0.95em; }
    .note { margin-top: 30px; font-size: 0.9em; color: #a00; }
    .buttons {
      display: flex;
      justify-content: flex-end;
      gap: 15px;
      margin-top: 40px;
    }
    .btn {
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
      border: none;
      color: white;
      background-color: #ea6a2e;
    }
    .btn:hover { background-color: #c2541c; }
    .required { color: red; }
  </style>
</head>
<body>
<header>
  <h1>KLE Society's<br><small>College of Computer Application, Dharwad</small></h1>
</header>
<div class="container">
  <div class="nav">
    <div class="step completed"><div class="circle"></div> Important Instructions</div>
    <div class="step completed"><div class="circle"></div> Category Details</div>
    <div class="step completed"><div class="circle"></div> Program Details</div>
    <div class="step active"><div class="circle"></div> Personal Details</div>
    <div class="step"><div class="circle"></div> Guardian and Address Details</div>
    <div class="step"><div class="circle"></div> Declaration</div>
    <div class="step"><div class="circle"></div> Payments</div>
  </div>
  
  <!-- Upload Section -->
<div class="upload-section">
  <h2>📄 Document Upload</h2>

  <div class="upload-instructions">
    <strong>Photograph:</strong> Recent passport‑size photo (JPEG/JPG, ≤ 2 MB).<br>
    <strong>Signature:</strong> Signed on white paper (JPEG/JPG, ≤ 2 MB).<br>
    <strong>Aadhar:</strong> PDF only, ≤ 2 MB.
  </div>

  <!-- 1️⃣ three inputs – no individual buttons -->
  <div class="upload-box">
    <div class="upload-field">
      <label><span class="required">*</span> Passport Size Photo</label>
      <input id="photoInput" type="file" accept=".jpg,.jpeg" required>
      <img id="photoPreview" style="max-width:120px;display:none;margin-top:10px;">
      <div id="photoName" class="file-info"></div>
    </div>

    <div class="upload-field">
      <label><span class="required">*</span> Signature</label>
      <input id="signatureInput" type="file" accept=".jpg,.jpeg" required>
      <img id="signaturePreview" style="max-width:120px;display:none;margin-top:10px;">
      <div id="signatureName" class="file-info"></div>
    </div>

    <div class="upload-field">
      <label><span class="required">*</span> Government ID Proof (Aadhar)</label>
      <input id="aadharInput" type="file" accept=".pdf" required>
      <div id="aadharName" class="file-info"></div>
    </div>
  </div>

  <!-- 2️⃣ single “Upload All Files” button -->
  <button id="uploadAllBtn" class="upload-button" disabled>Upload All Files</button>

  <div class="note">Wrong input or document will invalidate your application.</div>

  <div class="buttons">
    <button class="btn" onclick="window.history.back()">Previous</button>
    <button class="btn" id="nextBtn" onclick="goToNext('GandA-details.html')" disabled>Next</button>
  </div>
</div>

</div>

<script>
const state = { photo:false, signature:false, aadhar:false };

/* ---------- enable main button when all chosen ---------- */
['photo','signature','aadhar'].forEach(type => {
  const input = document.getElementById(type+'Input');
  input.addEventListener('change', () => {
    showPreview(type, input.files[0]);
    state[type] = !!input.files.length;
    document.getElementById('uploadAllBtn').disabled =
      !(state.photo && state.signature && state.aadhar);
  });
});

/* ---------- preview helper ---------- */
function showPreview(type, file) {
  const img = document.getElementById(type+'Preview');
  const nameDiv = document.getElementById(type+'Name');
  if (!file) { img.style.display='none'; nameDiv.textContent=''; return; }
  nameDiv.textContent = file.name;
  if (file.type.startsWith('image/')) {
    const reader = new FileReader();
    reader.onload = e => { img.src = e.target.result; img.style.display='block'; };
    reader.readAsDataURL(file);
  }
}

/* ---------- main upload ---------- */
document.getElementById('uploadAllBtn').addEventListener('click', async () => {
  document.getElementById('uploadAllBtn').disabled = true;

  try {
    await uploadSingle('photo',     photoInput.files[0]);
    await uploadSingle('signature', signatureInput.files[0]);
    await uploadSingle('aadhar',    aadharInput.files[0]);

    alert('All documents uploaded successfully!');
    document.getElementById('nextBtn').disabled = false;   // enable Next
  } catch (err) {
    alert(err.message || 'Upload failed.');
    document.getElementById('uploadAllBtn').disabled = false;
  }
});

/* sends ONE file (handler expects file + type) */
async function uploadSingle(type, file) {
  const fd = new FormData();
  fd.append('file', file);
  fd.append('type', type);

  const res  = await fetch('upload-handler.php', { method:'POST', body:fd });
  const data = await res.json();
  if (data.status !== 'success') throw new Error(data.message);
}

/* ---------- “Next” button guard ---------- */
function goToNext(page) {
  if (state.photo && state.signature && state.aadhar) {
    window.location.href = page;
  } else {
    alert('Please upload all required documents first.');
  }
}

/* ---------- pre‑populate previews if files already exist (optional) ---------- */
<?php
  // PHP variables set earlier in the file (photoPath, signaturePath, aadharPath)
  echo "const existing = {
          photo:     '".addslashes($photoPath ?? '')."',
          signature: '".addslashes($signaturePath ?? '')."',
          aadhar:    '".addslashes($aadharPath ?? '')."'
        };";
?>
window.onload = () => {
  if (existing.photo) {
    document.getElementById('photoPreview').src = existing.photo;
    document.getElementById('photoPreview').style.display = 'block';
    document.getElementById('photoName').textContent = existing.photo.split('/').pop();
    state.photo = true;
  }
  if (existing.signature) {
    document.getElementById('signaturePreview').src = existing.signature;
    document.getElementById('signaturePreview').style.display = 'block';
    document.getElementById('signatureName').textContent = existing.signature.split('/').pop();
    state.signature = true;
  }
  if (existing.aadhar) {
    document.getElementById('aadharName').textContent = existing.aadhar.split('/').pop();
    state.aadhar = true;
  }
  /* enable buttons accordingly */
  document.getElementById('uploadAllBtn').disabled =
    !(state.photo && state.signature && state.aadhar);
  document.getElementById('nextBtn').disabled =
    !(state.photo && state.signature && state.aadhar);
};


</script>
</body>
</html>
