<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::select(
            "INSERT INTO forms (name)  
            VALUES('TAB FC'),
            ('I-INF'),
            ('I-VIAL'),
            ('D-SOL'),
            ('D-CRM'),
            ('ORAL DRP'),
            ('OTH NS FRM'),
            ('TAB'),
            ('LOZ'),
            ('SYR'),
            ('OTH FRM'),
            ('ORAL PAST'),
            ('TAB CT'),
            ('ORAL PWD'),
            ('ORAL SOL'),
            ('TAB EC'),
            ('CAP'),
            ('TAB RT'),
            ('CAP RT'),
            ('VG-TAB'),
            ('ORAL SPR'),
            ('VG-CAPS'),
            ('EAR-DRPS'),
            ('EY-SOL'),
            ('D-PWD'),
            ('VG-SOL'),
            ('N-HUM FRM'),
            ('D-GEL'),
            ('TAB EFF'),
            ('SUSP'),
            ('EY-DRPS'),
            ('NS-SPR'),
            ('I-AMP'),
            ('OTH FRM (TEAS)'),
            ('D-OINT'),
            ('SUPP'),
            ('LUNG-INH'),
            ('OTH FRM (GLOBUL)'),
            ('I-SYRNG'),
            ('OTH ORAL FRM'),
            ('TAB CHW'),
            ('I-CART'),
            ('OTH FRM (SWEET)'),
            ('OTH FRM (BATHS)'),
            ('D-SPR'),
            ('VG-SUPP'),
            ('TAB OD'),
            ('NS-GEL'),
            ('TAB SUBL'),
            ('EY-OINT'),
            ('OTH LUNG FRM'),
            ('NS-SOL'),
            ('CAP EC'),
            ('NS-PWD'),
            ('NS-OINT'),
            ('ORAL OIL'),
            ('TAB SOLB'),
            ('D-PAST'),
            ('TAB BUC'),
            ('EY-LOT'),
            ('VG-CRM'),
            ('EY-GEL'),
            ('NS-INH'),
            ('VG-GEL'),
            ('NS-CRM'),
            ('VG-OINT'),
            ('OTH FRM (IMPLNT)'),
            ('OTH VG FRM'),
            ('OTH EY FRM'),
            ('OTH SYS SOL'),
            ('ENEM-SOL'),
            ('OTH SYS TAB'),
            ('ENEM-PWD'),
            ('NS-DRP'),
            ('ENEM-GEL'),
            ('EY-CRM')
        ");
    }
}
