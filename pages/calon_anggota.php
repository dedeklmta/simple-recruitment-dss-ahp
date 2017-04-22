<?php
$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
	$sql = $mysqli->query("SELECT * FROM calon_anggota WHERE nim='$_GET[key]'");
	$row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$validasi = false; $err = false;
	if ($update) {
		$sql = "UPDATE calon_anggota SET nim='$_POST[nim]', nama='$_POST[nama]', tempat_lahir='$_POST[tempat_lahir]', tanggal_lahir='$_POST[tanggal_lahir]', kelamin='$_POST[kelamin]', alamat='$_POST[alamat]', no_hp='$_POST[no_hp]' WHERE nim='$_GET[key]'";
	} else {
		$sql = "INSERT INTO calon_anggota VALUES ('$_POST[nim]', '$_POST[nama]', '$_POST[tempat_lahir]', '$_POST[tanggal_lahir]', '$_POST[kelamin]', '$_POST[alamat]', '$_POST[no_hp]')";
		$validasi = true;
	}

	if ($validasi) {
		$q = $mysqli->query("SELECT nim FROM calon_anggota WHERE nim=$_POST[nim]");
		if ($q->num_rows) {
			$alert =  alert("warning", "<u>{$_POST["nim"]}</u> atas nama <u>{$_POST["nama"]}</u> sudah terdaftar!");
			$err = true;
		}
	}

    if (!$err AND $mysqli->query($sql)) {
        $alert =  alert("success", "<u>{$_POST["nim"]}</u> atas nama <u>{$_POST["nama"]}</u> berhasil disimpan!");
    } else {
        $alert =  alert("danger", "<u>{$_POST["nim"]}</u> atas nama <u>{$_POST["nama"]}</u> gagal disimpan!<hr>{$mysqli->error}");
    }
}
?>
<div class="row">
	<div class="col-md-4 hidden-print">
	    <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
            <div class="panel panel-<?= ($update) ? "warning" : "dark" ?>">
                <div class="panel-heading"><h3 class="text-center"><?= ($update) ? "EDIT" : "TAMBAH" ?></h3></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="nim">Nomor Induk Mahasiswa</label>
                        <input type="text" name="nim" class="form-control" <?= (!$update) ?: 'value="'.$row["nim"].'"' ?>>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" <?= (!$update) ?: 'value="'.$row["nama"].'"' ?>>
                    </div>
                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" <?= (!$update) ?: 'value="'.$row["tempat_lahir"].'"' ?>>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="text" name="tanggal_lahir" class="form-control datepicker" <?= (!$update) ?: 'value="'.$row["tanggal_lahir"].'"' ?>>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" class="form-control" <?= (!$update) ?: 'value="'.$row["alamat"].'"' ?>>
                    </div>
                    <div class="form-group">
                        <label for="no_hp">Nomor HP</label>
                        <input type="text" name="no_hp" class="form-control" <?= (!$update) ?: 'value="'.$row["no_hp"].'"' ?>>
                    </div>
                    <div class="form-group">
                        <label for="kelamin">Jenis Kelamin</label>
                        <select class="form-control" name="kelamin">
                            <option>---</option>
                            <option value="Pria" <?= (!$update) ?: (($row["kelamin"] != "Pria") ?: 'selected="on"') ?>>Pria</option>
                            <option value="Wanita" <?= (!$update) ?: (($row["kelamin"] != "Wanita") ?: 'selected="on"') ?>>Wanita</option>
                        </select>
                    </div>
                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-<?= ($update) ? "warning" : "dark" ?> btn-block">Simpan</button>
                    <?php if ($update): ?>
                        <a href="?page=calon_anggota" class="btn btn-default btn-block">Batal</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
	</div>
	<div class="col-md-8">
        <?=(isset($alert)) ? $alert : "" ?>
	    <div class="panel panel-dark">
	        <div class="panel-heading"><h3 class="text-center">DAFTAR CALON ANGGOTA</h3></div>
	        <div class="panel-body">
	            <table class="table table-condensed" id="calon_anggota">
	                <thead>
	                    <tr>
	                        <th>No</th>
	                        <th>NIM</th>
	                        <th>Nama</th>
	                        <th>Tempat, Tgl Lahir</th>
	                        <th>Alamat</th>
	                        <th>Jenis Kelamin</th>
	                        <th>Nomor HP</th>
	                        <th class="hidden-print"></th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <?php $no = 1; ?>
	                    <?php if ($query = $mysqli->query("SELECT * FROM calon_anggota")): ?>
	                        <?php while($row = $query->fetch_assoc()): ?>
	                        <tr id="<?=$row['nim']?>">
	                            <td><?=$no++?></td>
	                            <td><?=$row['nim']?></td>
	                            <td><?=$row['nama']?></td>
	                            <td><?=$row['tempat_lahir']?>, <?=$row['tanggal_lahir']?></td>
	                            <td><?=$row['alamat']?></td>
	                            <td><?=$row['kelamin']?></td>
	                            <td><?=$row['no_hp']?></td>
	                            <td class="hidden-print">
	                                <div class="btn-group">
	                                    <a href="?page=calon_anggota&action=update&key=<?=$row['nim']?>" class="btn btn-warning btn-xs hidden-print">Edit</a>
                                        <buttpn role="button" onClick="deleteRecord('calon_anggota', 'nim', <?=$row['nim']?>)" class="btn btn-danger btn-xs hidden-print">Hapus</buttpn>
	                                </div>
	                            </td>
	                        </tr>
	                        <?php endwhile ?>
	                    <?php endif ?>
	                </tbody>
	            </table>
	        </div>
            <div class="panel-footer hidden-print">
                <button type="submit" onClick="window.print();return false" class="btn btn-dark"><i class="glyphicon glyphicon-print"></i></button>
            </div>
	    </div>
	</div>
</div>