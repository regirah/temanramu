// Ambil semua elemen kategori
const categories = document.querySelectorAll('.category');

// Tambahkan event listener untuk setiap kategori
categories.forEach(category => {
    category.addEventListener('click', function() {
        // Hapus kelas 'active' dari semua kategori
        categories.forEach(cat => cat.classList.remove('active'));

        // Tambahkan kelas 'active' pada kategori yang diklik
        this.classList.add('active');
    });
});


// dropdown detail tanheb
// Script untuk toggle dropdown saat tombol diklik
document.querySelectorAll('.dd-button').forEach(button => {
  button.addEventListener('click', function() {
    const dropdown = this.parentElement; // Mengambil parent dari tombol
    const icon = this.querySelector('.dd-icon'); // Menargetkan ikon dalam tombol
    dropdown.classList.toggle('active'); // Menambahkan/menghapus kelas active pada kontainer
    icon.classList.toggle('active'); // Menambahkan/menghapus kelas active pada ikon untuk flip
  });
});

// detailtanhebLogin.html
// Ambil tombol dan form
const openFormButton = document.getElementById('openForm');
const formDariUlasan = document.querySelector('.formdariulasan');

// Event listener untuk tombol "Buat Ulasan"
openFormButton.addEventListener('click', function() {
    // Toggle display dari form ulasan
    if (formDariUlasan.style.display === 'none' || formDariUlasan.style.display === '') {
        formDariUlasan.style.display = 'block';  // Menampilkan form
    } else {
        formDariUlasan.style.display = 'none';  // Menyembunyikan form
    }
});



