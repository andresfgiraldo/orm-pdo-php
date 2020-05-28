<?php
# ------------------------------------------------------------------------------
# MIT License

# Copyright (c) 2020  Andres Felipe Giraldo 
#                    github:     andresfgiraldo
#                    email:      andresfgiraldo@live.com
#                    LinkedIn:   https://www.linkedin.com/in/andresfgiraldo/

# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:

# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.

# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.
# ------------------------------------------------------------------------------

require_once '__DATABASE.php';

/*
 * @class
 * TABLE
 * 
 * @autor
 * Andres Felipe Giraldo
 * 
 * @purpose
 * Tiene como finalidad la interacción con la base de datos
 * otorgando metodos CRUD de manera dinamica a sus clases extendidas.
 *
 * @implementation
 * Las CLASES EXTENDIDAS de esta, deben tener como ATRIBUTOS, exactamente 
 * los mismos CAMPOS que tenga la TABLA que representa en la base de datos.
 */

class TABLE extends DATABASE
{
    /*
     * @var $table
     * Tabla de la base datos
     */
    private $table;


    /*
     * @var $PK
     * PRIMARY_KEY en la tabla. Array del la forma {Campo1, Campo2 ...}
     */
    private $PK = [];


    /*
     * @var $_select
     * String en el que se prepara el fragmento de la instrucion SQL [SELECT campo1, campo2 ...]. No se incluye aquí FROM, WHERE, etc.
     * @use
     * Es utilizado por los métodos "_select()"
     */
    private $_select;


    /*
     * @var $_from
     * String en el que se prepara el fragmento de la instrucion SQL [FROM tabla1]. No se incluye aquí SELECT, WHERE, etc.
     * @use
     * Es utilizado por los métodos "_select()" | "_delete()"
     */
    private $_from;


    /*
     * @var $_where
     * String en el que se prepara el fragmento de la instrucion SQL [WHERE condicion=valor]. No se incluye aquí SELECT, FROM, etc.
     *   - SOLO opera condiciones de la forma "A=B";
     *   - No se admite del tipo: | "A>B" | "A<B" | "A<>B" | "A BETWENN B AND C" | Ni otras
     * @use
     * Es utilizado por los métodos "_select()" | "_update()" | "_delete()"
     */
    private $_where;


    /*
     * @var $_order
     * String en el que se prepara el fragmento de la instrucion SQL [ORDER BY campo1, campo2 ...]. No se incluye aquí SELECT, WHERE, etc.
     * @use
     * Es utilizado por los métodos "_select()"
     */
    private $_order;


    /*
     * @var $_update
     * String en el que se prepara el fragmento de la instrucion SQL [UPDATE tabla1]. No se incluye aquí SET, WHERE, etc.
     * @use
     * Es utilizado por los métodos "_update()"
     */
    private $_update;


    /*
     * @var $_update_val
     * String en el que se prepara el fragmento de la instrucion SQL [SET campo1=valor1, campo2=valor2 ...]. No se incluye aquí UPDATE, WHERE, etc.
     * @use
     * Es utilizado por los métodos "_update()"
     */
    private $_update_val;


    /*
     * @var $_delete
     * String en el que se prepara el fragmento de la instrucion SQL [DELETE]. No se incluye aquí FROM, WHERE, etc.
     * @use
     * Es utilizado por los métodos "_delete()"
     */
    private $_delete;


    /*
     * @var $_insert
     * String en el que se prepara el fragmento de la instrucion SQL [INSER INTO tabla1 (campo1, campo2 ...)]. No se incluye aquí VALUES(valor1, valor2...).
     * @use
     * Es utilizado por los métodos "_insert()"
     */
    private $_insert;


    /*
     * @var $_insert_val
     * String en el que se prepara el fragmento de la instrucion SQL [VALUES (valor1, valor2 ...)]. No se incluye aquí INSERT INTO tabla1 (campo1, campo2...).
     * @use
     * Es utilizado por los métodos "_insert()"
     */
    private $_insert_val;


    /*
     * @method __construc
     * Metodo constructor del objeto DAO
     *
     * @params
     *      @var $table
     *          nombre de la tabla en base de datos
     *      @var $pk
     *          PRIMARY_KEY en la tabla. Array del la forma {Campo1, Campo2 ...}
     */
    public function __construct($table, $pk = array())
    {
        parent::__construct();
        $this->table = $table;

        if (!empty($pk)) {
            $this->PK = $pk;
        }
    }


    /*
     * @function _selected
     * Ejecuta instruccion SQL: SELECT
     *
     * @params
     *      @var $condiciones
     *          [ARRAY] condiciones para pasar al where, de la forma {campo1=>valor1, campo2=>valor2 ...}
     *          Si no se envia se extraen todos los REGISTROS de la tabla
     *      @var $columnas
     *          [ARRAY] columnas para pasar al select, de la forma {columna1, columna2 ...}
     *          Si no se envia se seleccionan todas las COLUMNAS de la tabla
     *      @var $orden
     *          [ARRAY] columnas para pasar al order, de la forma {columna1, columna2 ...}
     *          Si no se envia no se realiza ordenamiento
     *      @var $fAll
     *          [BOOLEAN] indica si se regresa una matriz bidimencional, aunque el resultado sea un solo registro. Ej: Array[0][campo1, campo2 ...]
     *          Si no se envia y el resultado es un solo registro se obtiene Array[campo1, campo2 ...]
     */
    protected function _selected($columnas = array(), $condiciones = array(), $orden = array(), $fAll = false, $fIdx = 'N')
    {

        // Si no se envio array columnas se toman todas
        if (empty($columnas)) {
            $columnas = array("*");
        }

        //Se prepara SELECT
        $this->_select = "SELECT " . implode(",", $columnas) . " ";

        //Se prepara FROM
        $this->from();

        //si existen condiciones, se prepara WHERE
        if (!empty($condiciones)) {
            $this->where($condiciones);
        }

        //si existen ordenamiento, se prepara ORDER BY
        if (!empty($orden)) {
            $this->order($orden);
        }

        //se inicia un prepare en la conexion
        $stmt = $this->getDb()->prepare($this->_select . $this->_from . $this->_where . $this->_order);

        //se asignan valores las variables where en el statament
        $this->PDOBindArray($stmt, $condiciones);

        try {
            //se ejecuta la consulta
            $stmt->execute();

            //se obtiene la cantidad de registros devueltos
            $registros = $stmt->rowCount();

            //se regresa matriz bidimencional
            if ($fAll || $registros > 1) {
                if ($fIdx == "S") {
                    return $stmt->fetchAll();
                } else {
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }

            //o se regresa arreglo
            if ($registros == 1) {
                return $stmt->fetch();
            }
            return null;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return null;
        }
    }

    /*
     * @function _deleted
     * Ejecuta instruccion SQL: DELETE
     *
     * @params
     *      @var $condiciones
     *          [ARRAY] condiciones para pasar al where, de la forma {campo1=>valor1, campo2=>valor2 ...}
     *          Si no se envia se borran todos los REGISTROS de la tabla
     */
    protected function _deleted($condiciones = array(), $seguro = 0)
    {
        if ($seguro == 1 || !empty($condiciones)) {

            //se prepara DELETE
            $this->_delete = "DELETE ";

            //se prepara FROM
            $this->from();

            //si existen condiciones, se prepara WHERE
            if (!empty($condiciones)) {
                $this->where($condiciones);
            }

            //se inicia un prepare en la conexion
            $stmt = $this->getDb()->prepare($this->_delete . $this->_from . $this->_where);

            //se asignan valores las variables where en el statament
            $this->PDOBindArray($stmt, $condiciones);

            try {
                $stmt->execute();
                return array("success" => true, "action" => "DELETE", "rows" => $stmt->rowCount());
            } catch (PDOException $ex) {
                return array("success" => false, "action" => "DELETE " . $this->table, "code" => $ex->getMessage());
            }
        } else {
            return array("success" => false, "action" => "DELETE " . $this->table, "code" => "No puede hacer DELETE sin WHERE sino confirma esta accion");
        }
    }

    /*
     * @function _update
     * Ejecuta instruccion SQL: UPDATE
     *
     * @params
     *      @var $columnas
     *          [ARRAY] columnas para pasar al update, de la forma {columna1 => valor1, columna2 => valor2 ...}
     *      @var $condiciones
     *          [ARRAY] condiciones para pasar al where, de la forma {campo1=>valor1, campo2=>valor2 ...}
     */
    protected function _updated($columnas = array(), $condiciones = array(), $seguro = 0)
    {

        if ($seguro == 1 || !empty($condiciones)) {

            //se prepara UPDATE
            $this->_update = "UPDATE $this->table SET ";

            //se indican las columnas con los nuevos valores
            $vals = "";
            foreach ($columnas as $k => $v) {
                if ($k != "PK") {
                    if (preg_match('/^[a-zA-Z0-9-_]+$/', $k)) {
                        $vals = $vals . "{$k}=:" . "{$k} , ";
                    }
                }
            }
            $this->_update_val = substr($vals, 0, -2);

            //si existen condiciones, se prepara WHERE
            if (!empty($condiciones)) {
                $this->where($condiciones);
            }

            //se inicia un prepare en la conexion
            $stmt = $this->getDb()->prepare($this->_update . $this->_update_val . $this->_where);

            //se asignan valores las variables where en el statament
            $this->PDOBindArray($stmt, $columnas);

            try {
                $stmt->execute();
                return array("success" => true, "action" => "UPDATE", "rows" => $stmt->rowCount());
            } catch (PDOException $ex) {
                return array("success" => false, "action" => "UPDATE " . $this->table, "code" => $ex->getMessage());
            }
        } else {
            return array("success" => false, "action" => "UPDATE " . $this->table, "code" => "No puede hacer UPDATE sin WHERE sino confirma esta accion");
        }
    }

    /*
     * @function _update
     * Ejecuta instruccion SQL: INSERT
     *
     * @params
     *      @var $columnas
     *          [ARRAY] columnas para pasar al insert, de la forma {columna1 => valor1, columna2 => valor2 ...}
     */
    protected function _inserted($columnas = array())
    {
        if (!empty($columnas)) {

            $cols = "";
            foreach ($columnas as $k => $v) {
                if ($k != "PK") {
                    if (preg_match('/^[a-zA-Z0-9-_]+$/', $k)) {
                        if ($v != "" && $v != null) {
                            $cols = $cols . "{$k} , ";
                        }
                    }
                }
            }

            $this->_insert = "INSERT INTO $this->table (" . substr($cols, 0, -2) . ")";

            $vals = " VALUES (";
            foreach ($columnas as $k => $v) {
                if ($k != "PK") {
                    if (preg_match('/^[a-zA-Z0-9-_]+$/', $k)) {
                        if ($v != "" && $v != null) {
                            $vals = $vals . ":{$k} , ";
                        }
                    }
                }
            }

            $this->_insert_val = substr($vals, 0, -3) . ")";

            $stmt = $this->getDb()->prepare($this->_insert . $this->_insert_val);

            $this->PDOBindArray($stmt, $columnas, "N");

            try {
                $stmt->execute();
                return array("success" => true, "action" => "INSERT", "id" => $this->getDb()->lastInsertId());
            } catch (PDOException $ex) {
                return array("success" => false, "action" => "INSERT " . $this->table, "code" => $ex->getMessage());
            }
        } else {
            return array("success" => false, "action" => "INSERT " . $this->table, "code" => "No puede hacer INSERT sin COLUMNAS");
        }
    }


    protected function from()
    {
        $this->_from = "FROM " . $this->table . " ";
    }


    protected function where($properties)
    {
        $condiciones = "WHERE ";
        foreach ($properties as $key => $value) {
            if ($key != "PK") {
                if (preg_match('/^[a-zA-Z0-9-_]+$/', $key)) {
                    $condiciones = $condiciones . "{$key}=:" . "{$key} AND ";
                }
            }
        }

        $this->_where = substr($condiciones, 0, -4);
    }

    protected function order($properties)
    {
        $this->_order = "ORDER BY " . implode(",", $properties);
    }


    /*
     * @function PDOBindArray
     * Reemplaza en el stmt las variables por los valores correspondientes
     *
     * @params
     *      @var $poStatement
     *          stmt preparado previamente. esta precedido del simobolo "&" para reemplazar el original despues de ser procesado.
     *      @var $paArray
     *          [ARRAY] columnas y valores para reemplazar las variables. de la forma {variable1 => valor1, variable2 => valor2 ...}
     */
    protected function PDOBindArray(&$poStatement, &$paArray, $permite_null = "S")
    {
        foreach ($paArray as $k => $v) {
            if ($k != "PK") {
                if (preg_match('/^[a-zA-Z0-9-_]+$/', $k)) {
                    if (
                        $permite_null == "S" ||
                        ($permite_null == "N" && ($v != "" && $v != null))
                    ) {
                        $poStatement->bindValue(':' . $k, $v);
                    }
                }
            }
        }
    }


    /*
     * @function getPK
     * Lee la llave primararia definida para la tabla, y realizar acciones seguras de UPDATE y DELETE
     *
     * @return
     *      [ARRAY] columnas que componen la llave. De la forma {columna1, columna2 ...}
     */
    protected function getPK()
    {
        if (sizeof($this->PK) > 0) {
            $strJSON = "[{";
            foreach ($this->PK as $key => $campo) {
                $strJSON .= "\"" . $campo . "\":\"" . $this->{$campo} . "\",";
            }
            $strJSON = substr($strJSON, 0, -1);
            $strJSON .= "}]";

            $arrayPK = json_decode($strJSON, true);
            return $arrayPK[0];
        }
        return null;
    }

    /*
     * @function setAudit
     * Provee los datos de auditoria para la creacion y actualizacion de registros
     */
    protected function setAudit($insert_or_update = 1) // $insert_or_update = 1:insert | 2:update
    {
        // Obtenga el usuario conectado a la aplicacion
        $current_user = @$_SESSION['app']['user']['username'];

        $current_date = date('Y-m-d H:i:s');

        if (property_exists($this, 'user_created') && $insert_or_update == 1) {
            @$this->user_created = $current_user;
        }

        if (property_exists($this, 'created_at') && $insert_or_update == 1) {
            @$this->created_at = $current_date;
        }

        if (property_exists($this, 'user_updated') && $insert_or_update == 2) {
            @$this->user_updated = $current_user;
        }

        if (property_exists($this, 'updated_at') && $insert_or_update == 2) {
            @$this->updated_at = $current_date;
        }

        return;
    }

    /*
    * ****************************************
    *  Getter and Setter [ALL]
    * ****************************************
    */
    public function getProperty($property)
    {
        return $this->{$property};
    }

    public function getThisAllProperties()
    {
        //Se restan los atributos de la clase TABLE para pasar unicamente los del modelo final
        return array_diff_key(get_object_vars($this), get_class_vars("TABLE"));
    }


    public function setProperty($property, $value)
    {
        $this->{$property} = $value;
    }

    public function setThisAllProperties($properties)
    {
        foreach ($properties as $key => $value) {
            if (preg_match('/^[a-zA-Z0-9-_]+$/', $key)) {
                if(property_exists($this, $key)){
                    $this->{$key} = $value;
                }
            }
        }
    }



    /*
    * ****************************************
    *  Methods CRUD - DAO
    * ****************************************
    */
    public function select()
    {
        $result = $this->_selected(array(), $this->getPK());
        if (!empty($result)) {
            $this->setThisAllProperties($result);
        }
    }


    public function selectAll($condiciones = array(), $columnas = array(), $orden = array())
    {
        return $this->_selected($columnas, $condiciones, $orden, true);
    }


    public function delete()
    {
        return $this->_deleted($this->getPK());
    }


    public function update($agregar_audit = 1)
    {
        if ($agregar_audit == 1) {
            $this->setAudit(2);
        }
        return $this->_updated($this->getThisAllProperties(), $this->getPK());
    }


    public function insert($agregar_audit = 1)
    {
        if ($agregar_audit == 1) {
            $this->setAudit(1);
        }
        return $this->_inserted($this->getThisAllProperties());
    }
}
