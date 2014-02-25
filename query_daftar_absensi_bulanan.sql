SELECT b.fscardno, 
       DATE_FORMAT(fttime, '%Y-%m-%d') as tanggal,
       (CASE WHEN e.masuk = 0 THEN 0 ELSE FROM_UNIXTIME(e.masuk) END) checkin, 
       (CASE WHEN f.keluar = 0 THEN 0 ELSE FROM_UNIXTIME(f.keluar) END) as checkout
FROM tactivities a
JOIN temployees b ON a.fsCardNo = b.fscardno
JOIN kepegawaian_unitkerja c ON b.fsIDNo = c.nip
JOIN unit_organisasi d ON d.id = c.unit_biro
JOIN v_checkin e ON e.fscardno = a.fscardno AND e.tanggal = DATE_FORMAT(a.fttime, '%Y-%m-%d')
JOIN v_checkout f ON f.fscardno = a.fscardno AND f.tanggal = DATE_FORMAT(a.fttime, '%Y-%m-%d')
WHERE UNIX_TIMESTAMP(DATE_FORMAT(fttime, '%Y-%m-%d')) BETWEEN UNIX_TIMESTAMP('2010-08-01') AND UNIX_TIMESTAMP('2010-08-10')
AND d.id = 'BL.01'
GROUP BY fscardno, DATE_FORMAT(fttime, '%Y-%m-%d');





