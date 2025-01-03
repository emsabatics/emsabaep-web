<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BuzonAtCiudadana extends Mailable
{
    use Queueable, SerializesModels;
    public $nombreabonado;
    public $emailabonado;
    public $detalle;
    public $fecha;
    public $telefono;
    public $cuenta;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nombreabonado,$emailabonado,$detalle,$telefono,$cuenta,$fecha)
    {
        $this->nombreabonado = $nombreabonado;
        $this->emailabonado = $emailabonado;
        $this->detalle = $detalle;
        $this->telefono = $telefono;
        $this->fecha = $fecha;
        $this->cuenta= $cuenta;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('atencionciudadana@emsaba.gob.ec', 'Atención Ciudadana')
            ->view('Mail.mail')
                   ->with([
                       'nombre' => $this->nombreabonado,
                       'email' => $this->emailabonado,
                       'detalle' => $this->detalle,
                       'telefono' => $this->telefono,
                       'cuenta' => $this->cuenta,
                       'fecha' => $this->fecha
                   ]);
    }
}
