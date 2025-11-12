<?php
	
/**
* Clase que modela una fila de valores de datos persistidos en la DB
*/
class RowDataObject  {
	private $properties;
	
	/**
	* Llena el objeto con los valores de un recordset
	*
	* @Param $arrValues Array asociativo de un recordset {@see odbc_fetch_array}
	* @Param $arrAliasNames Array con los nombres de las propiedades en los valores.
	* (Por defecto toma todos los nombres de los campos del recordset)
	*/
	public function populate(&$arrValues, $arrAliasNames = null) {
	    $this->populateFields($arrValues, array_keys($arrValues), $arrAliasNames);
	    return $this; // Permite encadenar la creación con carga de datos
	}
	/**
	* Llena el objeto con los valores de un recordset
	*
	* @Param $arrValues Array asociativo de un recordset {@see odbc_fetch_array}
	* @Param $arrFieldNames Array con los nombres de los campos de la tabla a persistir en objeto.
	* @Param $arrAliasNames Array con los nombres de las propiedades en los valores.
	* (Por defecto toma todos los nombres de los campos del recordset)
	*/
	public function populateFields(&$arrValues, $arrFieldNames, $arrAliasNames=null) {
		$aliased = !empty($arrAliasNames);
		for ($i=0; $i < count($arrFieldNames); $i++) {
			$aIdx = empty($arrAliasNames) ? $arrFieldNames[$i] : $arrAliasNames[$i];
			$fIdx = $arrFieldNames[$i];
			$this->properties[strtolower($aIdx)] = $arrValues[$fIdx];
		}
	}
	
	/**
	* Obtiene la propiedad del objeto.
	* Previamente debe haber sido llamado el metodo #populate
	*/
	public function getProperty($propName) {
	    $propNameLower = strtolower($propName);
	    foreach ($this->properties as $key => $value) {
	        if (strtolower($key) === $propNameLower) {
	            // Si es DateTime, lo formateamos bonito
	            if ($value instanceof DateTime) {
	                return $value->format('d/m/Y');
	            }
	            // Retornamos el valor aunque sea vacío o null
	            return $value ?? "";
	        }
	    }
	    // Si no existe la propiedad, no lanzamos excepción, solo devolvemos vacío
	    return "";
	}
	
	
	/**
	* Obtiene todas las propiedades (campos)
	* Returns Array con valores de la fila
	*/
	public function getProperties() {
		return $this->properties;
	}
	
	/**
	* Funcion que verifica si la fila no tiene valores.
	* En esta clase siempre devuelve false.
	*/
	public function isEmpty() {
		return false;
	}
	
	public function __toString() {
		if (!empty($this->properties)) {
			$result = 'RowDataObject: {';		
			foreach (array_keys($this->properties) as $propName) {
				$result .= "$propName|";
			}
			return "$result}";
		} else {
			return 'RowDataObject: {NO-DATA}';
		}
	}
	public function __get($name) {
	    $name = strtolower($name);
	    return $this->properties[$name] ?? null;
	}
}

/**
* Clase que modela una fila sin valores
* Usado en @see{DataObject#getUniqueData}
*/
class EmptyRowDataObject extends RowDataObject {
	public function getProperty($name) {
		return '';
	}
	
	public function isEmpty() {
		return true;
	}
	
	public function __toString() {
		return 'EmptyRowDataObject';
	}
}

/**
* Clase que modela los datos devueltos en una consulta SQL.
* Contiene una matriz de 2 dimensiones.
* La 1ra dimension corresponden a las filas devueltas y
* la segunda a los datos de las filas en un @see{RowDataObject}
*/
class DataObject {
	
	protected $data;
	
	public function __construct() {
		// Constructos vacio
	}
	
	
	/**
	* Llena su matriz de valores utilizando JDBC
	* @param $rs Recordset
	* @param $arrFieldNames Campos a seleccionar de la consulta
	* @param $arrAliasNames (Opcional) Alias de las properties a 
	* usar en la matriz de datos
	*/

	
	protected function autoPopulateFields($rs, $arrFieldNames, $arrAliasNames=null) {
		while ( ! $rs->EOF ) {
			$rdo = new RowDataObject();
			$row = $rs->fields;
			$rdo->populateFields($row, $arrFieldNames, $arrAliasNames);
			$this->data[] = $rdo;
			$rs->MoveNext();
			//adodb_movenext($rs); //Use this with ADOdb extension for improve performance
		}
	}
	
	
	/**
	* Llena su matriz de valores utilizando JDBC
	* @param $rs Recordset
	* @param $arrAliasNames (Opcional) Alias de las properties a 
	* usar en la matriz de datos
	*/
	protected function autoPopulate($rs, $arrAliasNames = null) {
	    while (!$rs->EOF) {
	        $this->data[] = (new RowDataObject())->populate($rs->fields, $arrAliasNames);
	        $rs->MoveNext();
	        // adodb_movenext($rs); // Si usás ADOdb puro y querés más perfomance
	    }
	}
	
	/**
	* Devuelve los datos de la consulta como un array.
	* Cada fila del array es un objeto @link{RowDataObject}
	*/
	public function getData() {
		return $this->data;
	}
	
	/**
	 * Verifica si tiene datos el Data Object
	 */
	public function isEmpty() {
		return empty($this->data);
	}
	
	/**
	* Devuelve la primer fila de la consulta como un 
	* objeto @link{RowDataObject}
	* En caso de que no se disponga de datos, se devuelve
	* un objeto @link{EmptyRowDataObject}
	*/
	public function getUniqueData() {
	    return !empty($this->data) ? $this->data[0] : null;
	}
	
	/**
	* Devuelve los datos de la consulta como una matriz de dos dimensiones.
	* La 1ra dimension corresponde a las filas de la consulta 
	* y la 2da a las columnas de cada fila.
	*/
	public function getDataAsMatrix() {
		$result = array();
		foreach ($this->data as $rdo) {
			$result[] = $rdo->getProperties();
		}
		return $result;
	}
	
	public function __toString() {
		$result = '<DATA OBJECT> ';
		if (!empty($this->data)) {
			return $result . $this->data[0] . " - COUNT: " . count($this->data);
		}
		return $result;
	}
	
	public function getProperty($propName) {
	    if (empty($this->data)) {
	        throw new Exception("DataObject sin datos internos.");
	    }
	    $row = $this->data[0]; // RowDataObject
	    return $row->getProperty($propName);
	}
}


?>