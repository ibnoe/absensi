INSERT INTO absensi_pegawai 
SELECT c.userid, c.tanggal, 
       UNIX_TIMESTAMP(CONCAT(c.tanggal, ' ', masuk)), 
       UNIX_TIMESTAMP(CONCAT(c.tanggal, ' ', keluar)), 
       NULL
FROM (
SELECT a.userid, b.tanggal
FROM pegawai_registry a, hari_kerja b ) c
LEFT JOIN v_masuk d ON d.userid = c.userid AND d.tanggal = c.tanggal
LEFT JOIN v_keluar e ON e.userid = c.userid AND e.tanggal = c.tanggal