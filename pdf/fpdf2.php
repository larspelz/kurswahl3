<?php
$fd = fopen("test2.pdf", "w");
$pdfdoc = pdf_open($fd);
pdf_begin_page($pdfdoc, 421, 595);
pdf_set_font($pdfdoc, "Times-Roman", 24, "host");
pdf_set_text_pos($pdfdoc, 100, 100);
pdf_show($pdfdoc, "Hallo");
pdf_end_page($pdfdoc);
pdf_close($pdfdoc);
fclose($fd);
?>