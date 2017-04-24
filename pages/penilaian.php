<?php
$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
	$sql = $mysqli->query("SELECT * FROM penilaian WHERE id_penilaian='$_GET[key]'");
	$row = $sql->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$err = false;
	if ($update) {
		$sql = "UPDATE penilaian SET id_kriteria='$_POST[id_kriteria]', nim='$_POST[nim]', nilai='$_POST[nilai]' WHERE id_penilaian='$_GET[key]'";
	} else {
		$sql = "INSERT INTO penilaian VALUES (NULL, '$_POST[id_kriteria]', '$_POST[nim]', '$_POST[nilai]')";
	}

    if (!$err AND $mysqli->query($sql)) {
        $alert =  alert("success", "Penilaian untuk <u>{$_POST["nim"]}</u> berhasil disimpan!");
    } else {
        $alert =  alert("danger", "Penilaian untuk <u>{$_POST["nim"]}</u> gagal disimpan!<hr>{$mysqli->error}");
    }
}
?>
<div class="row">
    <?php if (!isset($_GET["laporan"])): ?>
	<div class="col-md-4 hidden-print">
	    <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
            <div class="panel panel-<?= ($update) ? "warning" : "dark" ?>">
                <div class="panel-heading"><h3 class="text-center"><?= ($update) ? "EDIT" : "TAMBAH" ?></h3></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="nim">Mahasiswa</label>
                        <select class="form-control" name="nim">
                            <option>---</option>
                            <?php $q = $mysqli->query("SELECT * FROM calon_anggota"); while ($r = $q->fetch_assoc()): ?>
                                <option value="<?=$r["nim"]?>" <?= (!$update) ?: (($row["nim"] != $r["nim"]) ?: 'selected="on"') ?>><?=$r["nama"]?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_kriteria">Kriteria</label>
                        <select class="form-control" name="id_kriteria">
                            <option>---</option>
                            <?php $q = $mysqli->query("SELECT * FROM kriteria"); while ($r = $q->fetch_assoc()): ?>
                                <option value="<?=$r["id_kriteria"]?>" <?= (!$update) ?: (($row["id_kriteria"] != $r["id_kriteria"]) ?: 'selected="on"') ?>><?=$r["nama"]?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nilai">Nilai</label>
                        <input type="text" name="nilai" class="form-control" <?= (!$update) ?: 'value="'.$row["nilai"].'"' ?>>
                    </div>
                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-<?= ($update) ? "warning" : "dark" ?> btn-block">Simpan</button>
                    <?php if ($update): ?>
                        <a href="?page=penilaian" class="btn btn-default btn-block">Batal</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
	</div>
    <?php endif; ?>
	<div class="col-md-<?=(isset($_GET["laporan"])) ? "12" : "8"?>">
        <?=(isset($alert)) ? $alert : "" ?>
	    <div class="panel panel-dark">
	        <div class="panel-heading"><h3 class="text-center">DAFTAR PENILAIAN</h3></div>
	        <div class="panel-body">
	            <table class="table table-condensed" id="penilaian">
	                <thead>
	                    <tr>
	                        <th>No</th>
	                        <th>NIM</th>
	                        <th>Nama</th>
	                        <th>Kriteria</th>
	                        <th>Nilai</th>
	                        <th class="hidden-print"></th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <?php $no = 1; ?>
	                    <?php if ($query = $mysqli->query("SELECT p.id_penilaian, p.nilai, c.nim, c.nama, k.nama AS kriteria FROM penilaian p JOIN calon_anggota c USING(nim) JOIN kriteria k ON p.id_kriteria=k.id_kriteria")): ?>
	                        <?php while($row = $query->fetch_assoc()): ?>
	                        <tr id="<?=$row['id_penilaian']?>">
	                            <td><?=$no++?></td>
	                            <td><?=$row['nim']?></td>
	                            <td><?=$row['nama']?></td>
	                            <td><?=$row['kriteria']?></td>
	                            <td><?=$row['nilai']?></td>
	                            <td class="hidden-print">
	                                <div class="btn-group">
	                                    <a href="?page=penilaian&action=update&key=<?=$row['nim']?>" class="btn btn-warning btn-xs hidden-print">Edit</a>
                                        <buttpn role="button" onClick="deleteRecord('penilaian', 'id_penilaian', <?=$row['id_penilaian']?>)" class="btn btn-danger btn-xs hidden-print">Hapus</buttpn>
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