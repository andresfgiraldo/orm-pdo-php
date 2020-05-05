<?php

require_once(dirname(__FILE__) . '/__database/__TABLE.php');

class Pais extends TABLE
{
    protected $alfa2; // ISO 3166-1 alfa-2
    protected $alfa3; // ISO 3166-1 alfa-3
    protected $numerico; // ISO 3166-1 numérico
    protected $nombre; // Nombre del pais

    # Columnas de auditoria: SUGERIDAS (con estos nombres) para funcion setAudit() de Class TABLE.
    protected $user_created;
    protected $created_at;
    protected $user_updated;
    protected $updated_at;

    
    public function __construct($pais = null)
    {
        $table_name = 'gr_pais';
        $primary_key = array('alfa2');
        
        parent::__construct($table_name, $primary_key);

        if ($pais != null) {
            $this->alfa2 = $pais;
            $this->select();
        }
    }

}


# http://utils.mucattu.com/iso_3166-1.html
# CÓDIGO DE PAÍSES SEGÚN ISO 3166-1
# ISO 3166-1 como parte del estándar ISO 3166 proporciona códigos para los nombres de países y otras dependencias administrativas.
