SELECT b.fscardno, 
       DATE_FORMAT(fttime, '%Y-%m-%d') as tanggal,
       (CASE WHEN e.masuk = 0 THEN 0 ELSE FROM_UNIXTIME(e.masuk) END) checkin, 
       (CASE WHEN f.keluar = 0 THEN 0 ELSE FROM_UNIXTIME(e.keluar) END) as checkout
FROM tactivities a
JOIN temployees b ON a.fsCardNo = b.fscardno
JOIN kepegawaian_unitkerja c ON b.fsIDNo = c.nip
JOIN unit_organisasi d ON d.id = c.unit_biro
JOIN (SELECT fscardno, DATE_FORMAT(fttime, '%Y-%m-%d') as tanggal,
             UNIX_TIMESTAMP(DATE_FORMAT(fttime, '%Y-%m-%d %H:%m:%i')) as masuk, 0 as keluar
             FROM tactivities 
             WHERE fcdirflag = 1) e ON e.fscardno = a.fscardno AND e.tanggal = DATE_FORMAT(a.fttime, '%Y-%m-%d')
JOIN (SELECT fscardno, DATE_FORMAT(fttime, '%Y-%m-%d') as tanggal,
             0 as masuk, UNIX_TIMESTAMP(DATE_FORMAT(fttime, '%Y-%m-%d %H:%m:%i')) as keluar
             FROM tactivities 
             WHERE fcdirflag = 0) f ON f.fscardno = a.fscardno AND f.tanggal = DATE_FORMAT(a.fttime, '%Y-%m-%d')
WHERE UNIX_TIMESTAMP(DATE_FORMAT(fttime, '%Y-%m-%d')) BETWEEN UNIX_TIMESTAMP('2010-08-01') AND UNIX_TIMESTAMP('2010-08-07')
AND d.id = 'BL.01'
GROUP BY fscardno, DATE_FORMAT(fttime, '%Y-%m-%d');



