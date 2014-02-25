SELECT fsidno, fsname,
       SUM(CASE WHEN masuk IS NULL AND keluar IS NULL THEN 1 ELSE 0 END) as tidakhadir
FROM (
SELECT a.*, 
       FROM_UNIXTIME(b.masuk) as masuk, 
       FROM_UNIXTIME(d.keluar) as keluar,
       TIMESTAMPDIFF(MINUTE, CONCAT(DATE_FORMAT(FROM_UNIXTIME(b.masuk), '%Y-%m-%d'), ' 07:30:00'), FROM_UNIXTIME(b.masuk)) as tl,
       TIMESTAMPDIFF(MINUTE, FROM_UNIXTIME(d.keluar), CONCAT(DATE_FORMAT(FROM_UNIXTIME(d.keluar), '%Y-%m-%d'), ' 17:00:00')) as psw
FROM (
SELECT a.tanggal, b.fscardno, b.fsidno, b.fsname
FROM v_tactivities_date a, temployees b 
WHERE UNIX_TIMESTAMP(a.tanggal) BETWEEN UNIX_TIMESTAMP('2010-08-02') AND UNIX_TIMESTAMP('2010-08-11')) a
LEFT JOIN v_checkin b ON a.tanggal = b.tanggal AND a.fscardno = b.fscardno
LEFT JOIN v_checkout d ON a.tanggal = d.tanggal AND a.fscardno = d.fscardno
JOIN kepegawaian_unitkerja c ON a.fsidno = c.nip
WHERE c.unit_bagian = 'BL.022'
GROUP BY a.fscardno, a.tanggal
ORDER BY a.fscardno, a.tanggal ) a
GROUP BY fsidno