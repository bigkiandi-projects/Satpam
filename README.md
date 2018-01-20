# Satpam
<p align='center'>
  <img width='90' src='http://www.clipartlord.com/wp-content/uploads/2014/09/policeman4-145x240.png'/>
</p>

Belajar implementasi Library sederhana untuk login, logout, buat user, hapus user, dan manajemen session di Codeigniter.

* Versi BETA release 1.0.1

Fitur utama :
* Support CodeIgniter 2.0 AND 3.0
* Password hashing
* Simple dan cocok untuk pemula sebagai bahan belajar.
* Validasi duplicate email input
## Demo
Tersedia di folder Demo.
## Install
* Salin file `Satpam.php` ke folder **Application/Library**
* Muat library nya di controller kamu `$this->load->library('satpam');`
* Library ini secara default membutuhkan struktur table seperti di bawah. Copy dan jalankan query nya.
```
CREATE TABLE `users` (
`user_id` int(10) unsigned NOT NULL auto_increment,
`user_name` varchar(255) NOT NULL default '',
`user_email` varchar(255) NOT NULL default '',
`user_pass` varchar(255) NOT NULL default '',
`user_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
`user_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
`user_last_login` datetime DEFAULT NULL,
PRIMARY KEY  (`user_id`),
UNIQUE KEY `user_email` (`user_email`)
) DEFAULT CHARSET=utf8;
```

Atau liyat di folder demo.

## Gimana cara pakeknya?
Ada 6 buat method utama dari Class Satpam ini.
#### `buatUserBaru($user_name, $email, $pass, $role, $autologin)`
fungsi ini membutuhkan 5 parameter seperti deskripsi di atas. parameter `$autologin` jika bernilai `true` berfungsi untuk ketika user selesai atau berhasil submit daftar, maka secara otomatis lansung login. Jika nilai `false`, maka user harus login dulu setelah selesai daftar.
Contoh proses daftar di controller kamu :
```
public function daftar() {
  $user_name = $this->input->post('user_name');
  $email = $this->input->post('email');
  $pass = $this->input->post('pass');
  $role = $this->input->post('role');

  if ($this->input->post('autologin') == 1) {
    $autologin = true;
  } else {
    $autologin = false;
  }

  if($this->satpam->BuatUserBaru($user_name, $email, $pass, $role, $autologin)) {
    echo "tambah user berhasil";
  } else {
    echo "gagal";
  }
}
```
#### `CekLogin($email, $passwd)` 

fungsi ini membutuhkan 2 parameter input. parameter pertama adalah email dan yang kedua password. Jadi, ketika kamu mau melakukan proses cek login, cukup `ceklogin($email, $password)` maka si Satpam will do everything for you.

Contoh implementasi di controller kamu :


```
public function login(){

  $email = $this->input->post('email');
  $pass = $this->input->post('pass');
  
  if ($this->satpam->CekLogin($email, $pass)) {
    echo "berhasil";
  } else{
    echo "gagal total";
  }
  
}
```
#### `cekStatus()`
fungsi ini tidak membutuhkan parameter input. Fungsi cekstatus() di gunakan untuk proses identifikasi apakah user sudah login ataukah belum? Segera setelah user login, cekstatus() akan memvalidasi akses user. Jika kamu ingin memfilter akses user terhadap suatu halaman, cukup panggil fungsi ini di layer-layer if else kamu. Contoh implementasi :
```
public function member_area() {
  if($this->satpam->cekStatus()) {
    $data['info'] = $this->satpam->cekInfo();
    $this->load->view('member_area', $data);
  } else {
    $this->session->set_flashdata('fail', 'anda belum login.');
    redirect(base_url());
  }
}
  ```
Pada dasarnya, fungsi di atas hanya untuk meload halaman bernama `member_area`. untuk mencegah akses dari user yang tidak login, cek status akan melakukan validasi terlebih dahulu. Simpel kan?

#### cekInfo()
Fungsi ini tidak membuthkan parameter apapun. Fungsinya adalah untuk mengetahui detail informasi dari user yang sedang login. Contoh implementasi :
```
$data['info'] = $this->satpam->cekInfo();
$this->load->view('member_area', $data);
```
di view di halaman member_area, print_r() dari variable $info hasilnya :
```
Array
(
    [user_id] => 16
    [user_name] => angga
    [user_email] => angga@gmail.com
    [user_pass] => $2y$11$QUeC2h1qHVa461L1NmWzwOb3AgErQ5RR4HohEAdjlNp0Vtj8mTeyG
    [user_level] => admin
    [user_date] => 2018-01-20 00:09:40
    [user_modified] => 2018-01-20 00:20:55
    [user_last_login] => 2018-01-20 00:21:22
)
```
#### hapus($id)
Menghapus users berdasarkan id. 1 parameter berisi user_id
#### logout()
Tidak membuthkan parameter. cukup panggil maka otomatis logout.

### fungsi edit
Fungsi ini masih dalam proses penyempurnaan. Yang tersedia hanya fungsi `edit_password()` dan fungsi `edit_email()` namun masing-masing masih terbatas dalam proses edit email dan password saja dalam 2 url proses yang terpisah.

contoh implementasi edit email :
```
public function gantiemail() {
  $id = $this->input->post('id');
  $email = $this->input->post('email');  
  if ($this->satpam->edit_email($id, $email, $autologin)) {
    $this->session->set_flashdata('success', 'user berhasil di tambahkan');
    redirect(base_url('welcome/member_area'));
  }
}
```

### INGIN BERKONTRIBUSI? YES WE ARE OPEN.
### INGIN BERKONTRIBUSI TAPI NDAK TAU CARANYA MAEN GITHUB?
baca disini [Cara berkontribusi di proyek Open Source!](https://github.com/endymuhardin/belajarGit/blob/master/cara-berkontribusi-opensources-github.md) 
