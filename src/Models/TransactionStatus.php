<?php

declare(strict_types=1);

namespace Vanilo\Simplepay\Models;

use Konekt\Enum\Enum;

class TransactionStatus extends Enum
{
    //Létrejött tranzakció a SimplePay rendszerében
    public const INIT = 'INIT';

    //Időtúllépés INIT státuszban
    public const TIMEOUT = 'TIMEOUT';

    //A  fizetőoldalon megszakított fizetés, vagy a vásárló  elnavigál a
    //fizető oldalról, vagy bezárja a böngészőt.
    public const CANCELLED = 'CANCELLED';

    //Sikertelen authorizáció
    public const NOTAUTHORIZED = 'NOTAUTHORIZED';

    //Fizetés alatt, a „Fizetek” gomb megnyomása után
    public const INPAYMENT = 'INPAYMENT';

    //Vizsgálat alatt, csalásszűrés futása idejére
    public const INFRAUD = 'INFRAUD';

    //Sikeres authorizáció a kártyaadatok megadása után
    public const AUTHORIZED = 'AUTHORIZED';

    //Csalás gyanú
    public const FRAUD = 'FRAUD';

    //Zárolt összeg visszafordítva (kétlépcsős)
    public const REVERSED = 'REVERSED';

    //Visszatérítés (részleges, vagy teljes) Negatív összegű tranzakció
    public const REFUND = 'REFUND';

    //Sikeres, befejezett tranzakció
    public const FINISHED = 'FINISHED';
}
