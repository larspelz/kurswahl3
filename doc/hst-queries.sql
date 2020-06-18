-- Export der Schülerliste

select s.SNummer,s.Name,s.Vorname,l.Klasse,l.P10_Fach1,l.P10_Fach2 from suler1011_2 s, laufb1011_2 l where s.SNummer=l.SNummer and l.Klasse like '10%' order by l.Klasse,s.Name

INSERT INTO sndbx_0_schueler( snr, name, vorname, klasse, prof1, prof2 )
SELECT s.SNummer, s.Name, s.Vorname, l.Klasse, l.P10_Fach1, l.P10_Fach2
FROM hst.suler1314_1 s, hst.laufb1314_1 l
WHERE s.SNummer = l.SNummer
AND l.Klasse LIKE '10%'
ORDER BY l.Klasse, s.Name