<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Satpam - Library sederhana berisi class class super keren
 *
 * Proses Login, register menjadi lebih sederhana
 *
 * Library ini, menggunakan default database seperti di bawah ini. 
 * Harus sama, kalo endak, kamu perlu modif beberapa nama table dan kolom database kamu.
 *   
 * Untuk MYSQL 5.0 and 5.5 jalankan :
 *
 *   CREATE TABLE `users` (
 *     `user_id` int(10) unsigned NOT NULL auto_increment,
 *     `user_email` varchar(255) NOT NULL default '',
 *     `user_pass` varchar(255) NOT NULL default '',
 *	   `user_level` varchar(255) NOT NULL default '',
 *     `user_date` datetime NOT NULL default '0000-00-00 00:00:00',
 *     `user_modified` datetime NOT NULL default '0000-00-00 00:00:00',
 *     `user_last_login` datetime NULL default NULL,
 *     PRIMARY KEY  (`user_id`),
 *     UNIQUE KEY `user_email` (`user_email`),
 *   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 *
 * Untuk MYSQL 5.6 and more jalankan :
 *
 *   CREATE TABLE `users` (
 *     `user_id` int(10) unsigned NOT NULL auto_increment,
 *     `user_email` varchar(255) NOT NULL default '',
 *     `user_pass` varchar(255) NOT NULL default '',
 *	   `user_level` varchar(255) NOT NULL default '',
 *     `user_date` datetime NOT NULL default CURRENT_TIMESTAMP,
 *     `user_modified` datetime NOT NULL default CURRENT_TIMESTAMP,
 *     `user_last_login` datetime NULL default NULL,
 *     PRIMARY KEY  (`user_id`),
 *     UNIQUE KEY `user_email` (`user_email`),
 *   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 * 
 * @package   Satpam
 * @version   1.0.1
 * @author    Angga Cuacua <bigkianditeam[at]gmail.com>
 * @copyright Copyright (c) 2017-2018, bigkiandi-projects.
 * @license   http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      https://github.com/DaBourz/SimpleLoginSecure
 */

class Satpam {

	protected $CI; // CodeIgniter object
	protected $user_table = 'users'; // Nama tabel
	
	/**
	* Constructor
	* Get the current CI object
	*/
	public function __construct() {
        // Assign the CodeIgniter super-object
		$this->CI =& get_instance();
	}

	// buat user baru
	function BuatUserBaru($user_name,$user_email,$user_pass, $role, $auto_login = true) {
		//Make sure account info was sent
		if($user_email == '' OR $user_pass == '' OR $level='') {
			return false;
		}
		
		// Cek status user udah ada apa belom?
		$this->CI->db->where('user_email', $user_email); 
		$query = $this->CI->db->get_where($this->user_table);
		
		if ($query->num_rows() > 0) //user_email udah ada.
			return false;

		/**
		 * PASSWORD HASHING.
		 * NGAMBIL DARI OFFICIAL DOKUMENTASI DARI PHP. BUKAN NGARANG LHO YA..JADI AMAN.
		 */
		$options = [
		    'cost' => 11,
		    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
		];
		$user_pass_hashed = password_hash($user_pass, PASSWORD_BCRYPT, $options);

		// Simpan data user ke database
		$data = array(
					'user_name' => $user_name,
					'user_email' => $user_email,
					'user_level' => $role,
					'user_pass' => $user_pass_hashed,
					'user_date' => date('c'),
					'user_modified' => date('c'),
				);

		$this->CI->db->set($data); 
		if(!$this->CI->db->insert($this->user_table)) // kalo ndak berhasil di simpan, berarti ada masalah saat insert.
			return false;						
				
		if($auto_login)
			$this->CekLogin($user_email, $user_pass);
		return true;
	}

	function edit_email($user_id = null, $user_email = '', $auto_login = true) {

		if($user_id == null OR $user_email == '') {
			return false;
		}
		
		$this->CI->db->where('user_id', $user_id);
		$query = $this->CI->db->get_where($this->user_table);
		
		if ($query->num_rows() == 0){ // user don't exists
			return false;
		}
		
		$data = array(
					'user_email' => $user_email,
					'user_modified' => date('c'),
				);

		$this->CI->db->where('user_id', $user_id);
		if(!$this->CI->db->update($this->user_table, $data)) //There was a problem! 
			return false;						
				
		if($auto_login){
			$user_data['user_email'] = $user_email;
			
			$this->CI->session->set_userdata($user_data);
		}
		return true;
	}

	function edit_password($user_email = '', $old_pass = '', $new_pass = '') {
		
		// Check if the password is the same as the old one
		$this->CI->db->select('user_pass');
		$query = $this->CI->db->get_where($this->user_table, array('user_email' => $user_email));
		$user_data = $query->row_array();

		if (!password_verify($old_pass, $user_data['user_pass'])) {
			return FALSE;
		}
		
		$options = [
		    'cost' => 11,
		    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
		];
		$user_pass_hashed = password_hash($new_pass, PASSWORD_BCRYPT, $options);
		$data = array(
			'user_pass' => $user_pass_hashed,
			'user_modified' => date('c')
		);
		
		$this->CI->db->set($data);
		$this->CI->db->where('user_email', $user_email);
		if(!$this->CI->db->update($this->user_table, $data)){ // There was a problem!
			return FALSE;
		} else {
			return TRUE;
		}
	}

	// cek login
	function CekLogin($user_email='', $user_pass='') {

		// Cek dulu ni bocah submit form kosong apa kagak?
		if($user_email == '' OR $user_pass == '')
			return false;

		//Cek dulu ni bocah udah login pa kagak?
		if($this->CI->session->userdata('user_email') == $user_email)
			return true;

		//Cek lagi ke user table
		$this->CI->db->where('user_email', $user_email); 
		$query = $this->CI->db->get_where($this->user_table);
		
		if ($query->num_rows() > 0) { // kalo data yg di input user ada di database

			$user_data = $query->row_array(); // rekam datanya

			if (!password_verify($user_pass, $user_data['user_pass'])) // cek sama gak tuh sama password hasil hashing nya?
				return false;

			// Buat session yang lebih fresh, Terbaru, biar gak gitu-gitu aja ehe ehe ehe..
			if (CI_VERSION >= '3.0') {
				$this->CI->session->sess_regenerate(TRUE);
			} else {
				//Destroy old session
				$this->CI->session->sess_destroy();
				$this->CI->session->sess_create();
			}

			// rekam waktu terakhir user login dan simpan ke database.
			$this->CI->db->simple_query('UPDATE ' . $this->user_table  . ' SET user_last_login = "' . date('c') . '" WHERE user_id = ' . $user_data['user_id']);

			//Set session data
			unset($user_data['user_pass']);
			$user_data['user'] = $user_data['user_email'];
			$user_data['udah_login'] = true;
			$user_data['level'] = $user_data['level'];
			$this->CI->session->set_userdata($user_data);
			
			return true;

		} else {

			return false;

		}

	}

	// cek status login
	function cekStatus() {

		// Cek status user udah ada apa belom?
		$this->CI->db->where('user_email', $this->CI->session->userdata('user')); 
		$query = $this->CI->db->get_where($this->user_table);
		
		if ($query->num_rows() == 0) //user_email gak ada.
			return false;

		$user_data = $query->row_array();

		if ($this->CI->session->userdata('user') == $user_data['user_email']) {
			return true;
		} else {
			return false;
		}
	}
	// get info user yg lagi login
	function cekInfo() {
		$this->CI->db->where('user_email', $this->CI->session->userdata('user')); 
		$query = $this->CI->db->get_where($this->user_table);
		
		if ($query->num_rows() == 0) //user_email gak ada.
			return false;
		$user_data = $query->row_array();
		return $user_data;
	}

	// delete 
	function hapus($user_id) {
	
		if(!is_numeric($user_id))
			return false;			
		return $this->CI->db->delete($this->user_table, array('user_id' => $user_id));
	}

	// logout
	function logout() {	
		$this->CI->session->sess_destroy();
	}

}
?>
