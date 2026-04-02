function formatRupiah(element) {
    let value = element.value.replace(/[^0-9]/g, '');
    if (value) {
        element.value = new Intl.NumberFormat('id-ID').format(value);
    }
}
function konfirmasiHapus() {
    return confirm("⚠️ Yakin ingin menghapus data ini? Tidak bisa dikembalikan!");
}

function konfirmasiExport() {
    return confirm("📥 Apakah Anda ingin mengunduh laporan?");
}

function konfirmasiBackup() {
    return confirm("💾 Apakah Anda ingin melakukan backup database?");
}

function konfirmasiHapusKategori() {
    return confirm("⚠️ Yakin ingin menghapus kategori ini?");
}
function validasiForm() {
    const tanggal = document.querySelector('input[name="tanggal"]')?.value;
    const nama = document.querySelector('input[name="nama"]')?.value;
    const kategori = document.querySelector('select[name="kategori"]')?.value;
    const tipe = document.querySelector('select[name="tipe"]')?.value;
    const jumlah = document.querySelector('input[name="jumlah"]')?.value;

    if (!tanggal) {
        showError("📅 Tanggal harus diisi!");
        return false;
    }
    if (!nama || nama.trim() === '') {
        showError("✏️ Nama item harus diisi!");
        return false;
    }
    if (!kategori || kategori === '') {
        showError("🏷️ Kategori harus dipilih!");
        return false;
    }
    if (!tipe || tipe === '') {
        showError("📊 Tipe harus dipilih!");
        return false;
    }
    if (!jumlah) {
        showError("💰 Jumlah harus diisi!");
        return false;
    }
    const jumlahNumber = jumlah.replace(/[^0-9]/g, '');
    if (isNaN(jumlahNumber) || jumlahNumber <= 0) {
        showError("💰 Jumlah harus berupa angka yang valid!");
        return false;
    }
    return true;
}
function setActiveMenu() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    document.querySelectorAll('.sidebar a').forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage || (currentPage === '' && href === 'index.php')) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}
function showSuccess(message) {
    const div = document.createElement('div');
    div.className = 'success-message';
    div.innerHTML = '✅ ' + message;
    document.body.appendChild(div);
    setTimeout(() => {
        div.style.opacity = '0';
        div.style.transition = 'opacity 0.3s ease';
        setTimeout(() => div.remove(), 300);
    }, 3200);
}
function showError(message) {
    const div = document.createElement('div');
    div.className = 'error-message';
    div.innerHTML = '❌ ' + message;
    document.body.appendChild(div);
    setTimeout(() => {
        div.style.opacity = '0';
        div.style.transition = 'opacity 0.3s ease';
        setTimeout(() => div.remove(), 300);
    }, 3200);
}
function showWarning(message) {
    const div = document.createElement('div');
    div.className = 'warning-message';
    div.innerHTML = '⚠️ ' + message;
    document.body.appendChild(div);
    setTimeout(() => {
        div.style.opacity = '0';
        div.style.transition = 'opacity 0.3s ease';
        setTimeout(() => div.remove(), 300);
    }, 3200);
}
function disableFormOnSubmit(formSelector) {
    const form = document.querySelector(formSelector);
    if (form) {
        form.addEventListener('submit', function() {
            const button = form.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.textContent = '⏳ Sedang memproses...';
            }
        });
    }
}
document.addEventListener('DOMContentLoaded', function() {
    setActiveMenu();
    
    // Setup form submission handlers
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        if (form.method === 'POST') {
            form.addEventListener('submit', function() {
                const button = form.querySelector('button[type="submit"]');
                if (button) {
                    button.disabled = true;
                }
            });
        }
    });
});