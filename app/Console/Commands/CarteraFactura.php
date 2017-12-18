<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cartera\Factura1, App\Models\Cartera\Factura2, App\Models\Cartera\FelFactura, App\Models\Cartera\FelProducto, App\Models\Cartera\FelImpuestos;
use Log, DB;

class CarteraFactura extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cartera:factura';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para generar facturas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('Bienvenido a la rutina de factuas.');
        $ano = config('koi.app.ano');

        $mesi = $this->ask('Digite el mes con el cual inicia la rutina (1 a 12)');
        $anoi = $this->ask('Digite el año con el cual inicia la rutina ('.$ano. ' a '. date('Y').')' );

        $mesf = $this->ask('Digite el mes con el cual finaliza la rutina (1 a 12)');
        $anof = $this->ask('Digite el año con el cual finaliza la rutina ('.$ano. ' a '. date('Y').')');

        if( !($mesi > 0 && $mesi <= 12) || !($mesf > 0 && $mesf <= 12) ) {
            $this->error('El filtro de mes no es valido.');
            return;
        }

        if( $anoi < $ano || $anoi > date('Y') && $anof < $ano || $anof > date('Y') ) {
            $this->error('El rango de años permitidos es de '.$ano.' hasta '.date('Y'));
            return;
        }

        $fechai = $anoi."-".$mesi."-01";
        $fechaf = $anof."-".$mesf."-01";

        if( $fechai > $fechaf ) {
            $this->error('La fecha final no puede ser mayor a la inicial');
            return;
        }

        if( $this->confirm("La fecha inicial que ha digitado es '$fechai' y fecha fin '$fechaf'?", true) ){
            DB::beginTransaction();
            try{
                $query = Factura1::query();
                $query->join('factura2', function($join){
                    $join->on('factura1_numero', '=', 'factura2_numero');
                    $join->on('factura1_sucursal', '=', 'factura2_sucursal');
                });
                $query->whereRaw("factura1_fecha_elaboro > '$fechai'");
                $query->whereRaw("factura1_fecha_elaboro < '$fechaf'");
                $query->limit(5);
                $felfacturas = $query->get();

                foreach ($felfacturas as $item) {
                    $this->insertFelFactura( $item );
                }

                DB::rollback();
                $this->info('!OK Bitchess');

            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
                $this->error('No se pudo ejecutar la rutina con exito.');
            }
        }
    }

    public function insertFelFactura( $felfactura ){
        $newfelfactura = new FelFactura;
        $newfelfactura->id = '';
        $newfelfactura->tokenempresa = '';
        $newfelfactura->idtablaorigen = '';
        $newfelfactura->tipodocumento = '01';
        $newfelfactura->prefijo = '';
        $newfelfactura->consecutivo = '';
        $newfelfactura->fechafacturacion = '';
        $newfelfactura->ordencompra = '';
        $newfelfactura->moneda = 'COP';
        $newfelfactura->totalimportebruto = $felfactura->factura1_bruto;
        $newfelfactura->totalbaseimponible = '';
        $newfelfactura->totalfactura = '';
        $newfelfactura->mediopago = '';
        $newfelfactura->descripcion = '';
        $newfelfactura->incoterm = '';
        $newfelfactura->consecutivofacturamodificada = '';
        $newfelfactura->cufefacturamodificada = '';
        $newfelfactura->fechafacturamodificada = '';
        $newfelfactura->tipopersona = '';
        $newfelfactura->razonsocial = '';
        $newfelfactura->primernombre = '';
        $newfelfactura->segundonombre = '';
        $newfelfactura->primerapellido = '';
        $newfelfactura->segundoapellido = '';
        $newfelfactura->tipoidentificacion = '';
        $newfelfactura->numeroidentificacion = '';
        $newfelfactura->regimen = '';
        $newfelfactura->email = '';
        $newfelfactura->pais = '';
        $newfelfactura->departamento = '';
        $newfelfactura->ciudad = '';
        $newfelfactura->bariolocalidad = '';
        $newfelfactura->direccion = '';
        $newfelfactura->telefono = '';
        $newfelfactura->aplicafel = '';
        $newfelfactura->cufe = '';
        $newfelfactura->estadoactual = '';
        $newfelfactura->fecharespuesta = '';
        $newfelfactura->tokenpassword = '';
        $newfelfactura->rango = '';
        $newfelfactura->estatuspago = '';
        $newfelfactura->totaldescuentos = '';
        $newfelfactura->fechavencimiento = '';
    }

    public function insertFelProducto( $felproducto ){
        $newfelproducto = new FelProducto;
        $newfelproducto->id = '';
        $newfelproducto->idfactura = '';
        $newfelproducto->codigoproducto = '';
        $newfelproducto->descripcion = '01';
        $newfelproducto->referencia = '';
        $newfelproducto->cantidad = '';
        $newfelproducto->unidadmedida = '';
        $newfelproducto->valorunitario = '';
        $newfelproducto->descuento = '';
        $newfelproducto->preciosinimpuestos = '';
        $newfelproducto->preciototal = '';
        $newfelproducto->codigoimpuesto = '';
        $newfelproducto->porcentajeimpuesto = '';
        $newfelproducto->valorretenido = '';
        $newfelproducto->baseimponible = '';
    }

    public function insertFelImpuestos( $felimpuesto ){
        $newfel = new FelImpuestos;
        $newfel->id = '';
        $newfel->idfactura = '';
        $newfel->codigoproducto = '01';
        $newfel->codigoimpuesto = '';
        $newfel->porcentajeimpuesto = '';
        $newfel->valorretenido = '';
        $newfel->baseimponible = '';
    }
}
