<?php

/**
 * Base class that represents a row from the 'currency' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseCurrency extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        CurrencyPeer
	 */
	protected static $peer;

	/**
	 * The value for the code field.
	 * @var        string
	 */
	protected $code;

	/**
	 * The value for the is_default field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $is_default;

	/**
	 * The value for the rate field.
	 * Note: this column has a database default value of: '1'
	 * @var        string
	 */
	protected $rate;

	/**
	 * @var        array Contract[] Collection to store aggregation of Contract objects.
	 */
	protected $collContracts;

	/**
	 * @var        Criteria The criteria used to select the current contents of collContracts.
	 */
	private $lastContractCriteria = null;

	/**
	 * @var        array OutgoingPayment[] Collection to store aggregation of OutgoingPayment objects.
	 */
	protected $collOutgoingPayments;

	/**
	 * @var        Criteria The criteria used to select the current contents of collOutgoingPayments.
	 */
	private $lastOutgoingPaymentCriteria = null;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	// symfony behavior
	
	const PEER = 'CurrencyPeer';

	/**
	 * Applies default values to this object.
	 * This method should be called from the object's constructor (or
	 * equivalent initialization method).
	 * @see        __construct()
	 */
	public function applyDefaultValues()
	{
		$this->is_default = false;
		$this->rate = '1';
	}

	/**
	 * Initializes internal state of BaseCurrency object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [code] column value.
	 * 
	 * @return     string
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * Get the [is_default] column value.
	 * 
	 * @return     boolean
	 */
	public function getIsDefault()
	{
		return $this->is_default;
	}

	/**
	 * Get the [rate] column value.
	 * 
	 * @return     string
	 */
	public function getRate()
	{
		return $this->rate;
	}

	/**
	 * Set the value of [code] column.
	 * 
	 * @param      string $v new value
	 * @return     Currency The current object (for fluent API support)
	 */
	public function setCode($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->code !== $v) {
			$this->code = $v;
			$this->modifiedColumns[] = CurrencyPeer::CODE;
		}

		return $this;
	} // setCode()

	/**
	 * Set the value of [is_default] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Currency The current object (for fluent API support)
	 */
	public function setIsDefault($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->is_default !== $v || $this->isNew()) {
			$this->is_default = $v;
			$this->modifiedColumns[] = CurrencyPeer::IS_DEFAULT;
		}

		return $this;
	} // setIsDefault()

	/**
	 * Set the value of [rate] column.
	 * 
	 * @param      string $v new value
	 * @return     Currency The current object (for fluent API support)
	 */
	public function setRate($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->rate !== $v || $this->isNew()) {
			$this->rate = $v;
			$this->modifiedColumns[] = CurrencyPeer::RATE;
		}

		return $this;
	} // setRate()

	/**
	 * Indicates whether the columns in this object are only set to default values.
	 *
	 * This method can be used in conjunction with isModified() to indicate whether an object is both
	 * modified _and_ has some values set which are non-default.
	 *
	 * @return     boolean Whether the columns in this object are only been set with default values.
	 */
	public function hasOnlyDefaultValues()
	{
			if ($this->is_default !== false) {
				return false;
			}

			if ($this->rate !== '1') {
				return false;
			}

		// otherwise, everything was equal, so return TRUE
		return true;
	} // hasOnlyDefaultValues()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (0-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
	 * @param      int $startcol 0-based offset column which indicates which restultset column to start with.
	 * @param      boolean $rehydrate Whether this object is being re-hydrated from the database.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->code = ($row[$startcol + 0] !== null) ? (string) $row[$startcol + 0] : null;
			$this->is_default = ($row[$startcol + 1] !== null) ? (boolean) $row[$startcol + 1] : null;
			$this->rate = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 3; // 3 = CurrencyPeer::NUM_COLUMNS - CurrencyPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Currency object", $e);
		}
	}

	/**
	 * Checks and repairs the internal consistency of the object.
	 *
	 * This method is executed after an already-instantiated object is re-hydrated
	 * from the database.  It exists to check any foreign keys to make sure that
	 * the objects related to the current object are correct based on foreign key.
	 *
	 * You can override this method in the stub class, but you should always invoke
	 * the base method from the overridden method (i.e. parent::ensureConsistency()),
	 * in case your model changes.
	 *
	 * @throws     PropelException
	 */
	public function ensureConsistency()
	{

	} // ensureConsistency

	/**
	 * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
	 *
	 * This will only work if the object has been saved and has a valid primary key set.
	 *
	 * @param      boolean $deep (optional) Whether to also de-associated any related objects.
	 * @param      PropelPDO $con (optional) The PropelPDO connection to use.
	 * @return     void
	 * @throws     PropelException - if this object is deleted, unsaved or doesn't have pk match in db
	 */
	public function reload($deep = false, PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(CurrencyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = CurrencyPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collContracts = null;
			$this->lastContractCriteria = null;

			$this->collOutgoingPayments = null;
			$this->lastOutgoingPaymentCriteria = null;

		} // if (deep)
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      PropelPDO $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(CurrencyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseCurrency:delete:pre') as $callable)
			{
			  if (call_user_func($callable, $this, $con))
			  {
			    $con->commit();
			
			    return;
			  }
			}

			if ($ret) {
				CurrencyPeer::doDelete($this, $con);
				$this->postDelete($con);
				// symfony_behaviors behavior
				foreach (sfMixer::getCallables('BaseCurrency:delete:post') as $callable)
				{
				  call_user_func($callable, $this, $con);
				}

				$this->setDeleted(true);
				$con->commit();
			} else {
				$con->commit();
			}
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Persists this object to the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All modified related objects will also be persisted in the doSave()
	 * method.  This method wraps all precipitate database operations in a
	 * single transaction.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(CurrencyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseCurrency:save:pre') as $callable)
			{
			  if (is_integer($affectedRows = call_user_func($callable, $this, $con)))
			  {
			    $con->commit();
			
			    return $affectedRows;
			  }
			}

			if ($isInsert) {
				$ret = $ret && $this->preInsert($con);
			} else {
				$ret = $ret && $this->preUpdate($con);
			}
			if ($ret) {
				$affectedRows = $this->doSave($con);
				if ($isInsert) {
					$this->postInsert($con);
				} else {
					$this->postUpdate($con);
				}
				$this->postSave($con);
				// symfony_behaviors behavior
				foreach (sfMixer::getCallables('BaseCurrency:save:post') as $callable)
				{
				  call_user_func($callable, $this, $con, $affectedRows);
				}

				CurrencyPeer::addInstanceToPool($this);
			} else {
				$affectedRows = 0;
			}
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Performs the work of inserting or updating the row in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave(PropelPDO $con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = CurrencyPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNew(false);
				} else {
					$affectedRows += CurrencyPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collContracts !== null) {
				foreach ($this->collContracts as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collOutgoingPayments !== null) {
				foreach ($this->collOutgoingPayments as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			$this->alreadyInSave = false;

		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			if (($retval = CurrencyPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collContracts !== null) {
					foreach ($this->collContracts as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collOutgoingPayments !== null) {
					foreach ($this->collOutgoingPayments as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = CurrencyPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		$field = $this->getByPosition($pos);
		return $field;
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getCode();
				break;
			case 1:
				return $this->getIsDefault();
				break;
			case 2:
				return $this->getRate();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param      string $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                        BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. Defaults to BasePeer::TYPE_PHPNAME.
	 * @param      boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns.  Defaults to TRUE.
	 * @return     an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = CurrencyPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getCode(),
			$keys[1] => $this->getIsDefault(),
			$keys[2] => $this->getRate(),
		);
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = CurrencyPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setCode($value);
				break;
			case 1:
				$this->setIsDefault($value);
				break;
			case 2:
				$this->setRate($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
	 * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
	 * The default key type is the column's phpname (e.g. 'AuthorId')
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = CurrencyPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setCode($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setIsDefault($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setRate($arr[$keys[2]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(CurrencyPeer::DATABASE_NAME);

		if ($this->isColumnModified(CurrencyPeer::CODE)) $criteria->add(CurrencyPeer::CODE, $this->code);
		if ($this->isColumnModified(CurrencyPeer::IS_DEFAULT)) $criteria->add(CurrencyPeer::IS_DEFAULT, $this->is_default);
		if ($this->isColumnModified(CurrencyPeer::RATE)) $criteria->add(CurrencyPeer::RATE, $this->rate);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(CurrencyPeer::DATABASE_NAME);

		$criteria->add(CurrencyPeer::CODE, $this->code);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     string
	 */
	public function getPrimaryKey()
	{
		return $this->getCode();
	}

	/**
	 * Generic method to set the primary key (code column).
	 *
	 * @param      string $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setCode($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of Currency (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setCode($this->code);

		$copyObj->setIsDefault($this->is_default);

		$copyObj->setRate($this->rate);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getContracts() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addContract($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getOutgoingPayments() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addOutgoingPayment($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     Currency Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     CurrencyPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new CurrencyPeer();
		}
		return self::$peer;
	}

	/**
	 * Clears out the collContracts collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addContracts()
	 */
	public function clearContracts()
	{
		$this->collContracts = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collContracts collection (array).
	 *
	 * By default this just sets the collContracts collection to an empty array (like clearcollContracts());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initContracts()
	{
		$this->collContracts = array();
	}

	/**
	 * Gets an array of Contract objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Currency has previously been saved, it will retrieve
	 * related Contracts from storage. If this Currency is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Contract[]
	 * @throws     PropelException
	 */
	public function getContracts($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CurrencyPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collContracts === null) {
			if ($this->isNew()) {
			   $this->collContracts = array();
			} else {

				$criteria->add(ContractPeer::CURRENCY_CODE, $this->code);

				ContractPeer::addSelectColumns($criteria);
				$this->collContracts = ContractPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(ContractPeer::CURRENCY_CODE, $this->code);

				ContractPeer::addSelectColumns($criteria);
				if (!isset($this->lastContractCriteria) || !$this->lastContractCriteria->equals($criteria)) {
					$this->collContracts = ContractPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastContractCriteria = $criteria;
		return $this->collContracts;
	}

	/**
	 * Returns the number of related Contract objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Contract objects.
	 * @throws     PropelException
	 */
	public function countContracts(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CurrencyPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collContracts === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(ContractPeer::CURRENCY_CODE, $this->code);

				$count = ContractPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(ContractPeer::CURRENCY_CODE, $this->code);

				if (!isset($this->lastContractCriteria) || !$this->lastContractCriteria->equals($criteria)) {
					$count = ContractPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collContracts);
				}
			} else {
				$count = count($this->collContracts);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Contract object to this object
	 * through the Contract foreign key attribute.
	 *
	 * @param      Contract $l Contract
	 * @return     void
	 * @throws     PropelException
	 */
	public function addContract(Contract $l)
	{
		if ($this->collContracts === null) {
			$this->initContracts();
		}
		if (!in_array($l, $this->collContracts, true)) { // only add it if the **same** object is not already associated
			array_push($this->collContracts, $l);
			$l->setCurrency($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Currency is new, it will return
	 * an empty collection; or if this Currency has previously
	 * been saved, it will retrieve related Contracts from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Currency.
	 */
	public function getContractsJoinContractType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CurrencyPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collContracts === null) {
			if ($this->isNew()) {
				$this->collContracts = array();
			} else {

				$criteria->add(ContractPeer::CURRENCY_CODE, $this->code);

				$this->collContracts = ContractPeer::doSelectJoinContractType($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(ContractPeer::CURRENCY_CODE, $this->code);

			if (!isset($this->lastContractCriteria) || !$this->lastContractCriteria->equals($criteria)) {
				$this->collContracts = ContractPeer::doSelectJoinContractType($criteria, $con, $join_behavior);
			}
		}
		$this->lastContractCriteria = $criteria;

		return $this->collContracts;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Currency is new, it will return
	 * an empty collection; or if this Currency has previously
	 * been saved, it will retrieve related Contracts from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Currency.
	 */
	public function getContractsJoinSubjectRelatedByCreditorId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CurrencyPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collContracts === null) {
			if ($this->isNew()) {
				$this->collContracts = array();
			} else {

				$criteria->add(ContractPeer::CURRENCY_CODE, $this->code);

				$this->collContracts = ContractPeer::doSelectJoinSubjectRelatedByCreditorId($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(ContractPeer::CURRENCY_CODE, $this->code);

			if (!isset($this->lastContractCriteria) || !$this->lastContractCriteria->equals($criteria)) {
				$this->collContracts = ContractPeer::doSelectJoinSubjectRelatedByCreditorId($criteria, $con, $join_behavior);
			}
		}
		$this->lastContractCriteria = $criteria;

		return $this->collContracts;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Currency is new, it will return
	 * an empty collection; or if this Currency has previously
	 * been saved, it will retrieve related Contracts from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Currency.
	 */
	public function getContractsJoinSubjectRelatedByDebtorId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CurrencyPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collContracts === null) {
			if ($this->isNew()) {
				$this->collContracts = array();
			} else {

				$criteria->add(ContractPeer::CURRENCY_CODE, $this->code);

				$this->collContracts = ContractPeer::doSelectJoinSubjectRelatedByDebtorId($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(ContractPeer::CURRENCY_CODE, $this->code);

			if (!isset($this->lastContractCriteria) || !$this->lastContractCriteria->equals($criteria)) {
				$this->collContracts = ContractPeer::doSelectJoinSubjectRelatedByDebtorId($criteria, $con, $join_behavior);
			}
		}
		$this->lastContractCriteria = $criteria;

		return $this->collContracts;
	}

	/**
	 * Clears out the collOutgoingPayments collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addOutgoingPayments()
	 */
	public function clearOutgoingPayments()
	{
		$this->collOutgoingPayments = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collOutgoingPayments collection (array).
	 *
	 * By default this just sets the collOutgoingPayments collection to an empty array (like clearcollOutgoingPayments());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initOutgoingPayments()
	{
		$this->collOutgoingPayments = array();
	}

	/**
	 * Gets an array of OutgoingPayment objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Currency has previously been saved, it will retrieve
	 * related OutgoingPayments from storage. If this Currency is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array OutgoingPayment[]
	 * @throws     PropelException
	 */
	public function getOutgoingPayments($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CurrencyPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collOutgoingPayments === null) {
			if ($this->isNew()) {
			   $this->collOutgoingPayments = array();
			} else {

				$criteria->add(OutgoingPaymentPeer::CURRENCY_CODE, $this->code);

				OutgoingPaymentPeer::addSelectColumns($criteria);
				$this->collOutgoingPayments = OutgoingPaymentPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(OutgoingPaymentPeer::CURRENCY_CODE, $this->code);

				OutgoingPaymentPeer::addSelectColumns($criteria);
				if (!isset($this->lastOutgoingPaymentCriteria) || !$this->lastOutgoingPaymentCriteria->equals($criteria)) {
					$this->collOutgoingPayments = OutgoingPaymentPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastOutgoingPaymentCriteria = $criteria;
		return $this->collOutgoingPayments;
	}

	/**
	 * Returns the number of related OutgoingPayment objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related OutgoingPayment objects.
	 * @throws     PropelException
	 */
	public function countOutgoingPayments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CurrencyPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collOutgoingPayments === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(OutgoingPaymentPeer::CURRENCY_CODE, $this->code);

				$count = OutgoingPaymentPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(OutgoingPaymentPeer::CURRENCY_CODE, $this->code);

				if (!isset($this->lastOutgoingPaymentCriteria) || !$this->lastOutgoingPaymentCriteria->equals($criteria)) {
					$count = OutgoingPaymentPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collOutgoingPayments);
				}
			} else {
				$count = count($this->collOutgoingPayments);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a OutgoingPayment object to this object
	 * through the OutgoingPayment foreign key attribute.
	 *
	 * @param      OutgoingPayment $l OutgoingPayment
	 * @return     void
	 * @throws     PropelException
	 */
	public function addOutgoingPayment(OutgoingPayment $l)
	{
		if ($this->collOutgoingPayments === null) {
			$this->initOutgoingPayments();
		}
		if (!in_array($l, $this->collOutgoingPayments, true)) { // only add it if the **same** object is not already associated
			array_push($this->collOutgoingPayments, $l);
			$l->setCurrency($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Currency is new, it will return
	 * an empty collection; or if this Currency has previously
	 * been saved, it will retrieve related OutgoingPayments from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Currency.
	 */
	public function getOutgoingPaymentsJoinSubject($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CurrencyPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collOutgoingPayments === null) {
			if ($this->isNew()) {
				$this->collOutgoingPayments = array();
			} else {

				$criteria->add(OutgoingPaymentPeer::CURRENCY_CODE, $this->code);

				$this->collOutgoingPayments = OutgoingPaymentPeer::doSelectJoinSubject($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(OutgoingPaymentPeer::CURRENCY_CODE, $this->code);

			if (!isset($this->lastOutgoingPaymentCriteria) || !$this->lastOutgoingPaymentCriteria->equals($criteria)) {
				$this->collOutgoingPayments = OutgoingPaymentPeer::doSelectJoinSubject($criteria, $con, $join_behavior);
			}
		}
		$this->lastOutgoingPaymentCriteria = $criteria;

		return $this->collOutgoingPayments;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Currency is new, it will return
	 * an empty collection; or if this Currency has previously
	 * been saved, it will retrieve related OutgoingPayments from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Currency.
	 */
	public function getOutgoingPaymentsJoinBankAccount($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CurrencyPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collOutgoingPayments === null) {
			if ($this->isNew()) {
				$this->collOutgoingPayments = array();
			} else {

				$criteria->add(OutgoingPaymentPeer::CURRENCY_CODE, $this->code);

				$this->collOutgoingPayments = OutgoingPaymentPeer::doSelectJoinBankAccount($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(OutgoingPaymentPeer::CURRENCY_CODE, $this->code);

			if (!isset($this->lastOutgoingPaymentCriteria) || !$this->lastOutgoingPaymentCriteria->equals($criteria)) {
				$this->collOutgoingPayments = OutgoingPaymentPeer::doSelectJoinBankAccount($criteria, $con, $join_behavior);
			}
		}
		$this->lastOutgoingPaymentCriteria = $criteria;

		return $this->collOutgoingPayments;
	}

	/**
	 * Resets all collections of referencing foreign keys.
	 *
	 * This method is a user-space workaround for PHP's inability to garbage collect objects
	 * with circular references.  This is currently necessary when using Propel in certain
	 * daemon or large-volumne/high-memory operations.
	 *
	 * @param      boolean $deep Whether to also clear the references on all associated objects.
	 */
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
			if ($this->collContracts) {
				foreach ((array) $this->collContracts as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collOutgoingPayments) {
				foreach ((array) $this->collOutgoingPayments as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collContracts = null;
		$this->collOutgoingPayments = null;
	}

	// symfony_behaviors behavior
	
	/**
	 * Calls methods defined via {@link sfMixer}.
	 */
	public function __call($method, $arguments)
	{
	  if (!$callable = sfMixer::getCallable('BaseCurrency:'.$method))
	  {
	    throw new sfException(sprintf('Call to undefined method BaseCurrency::%s', $method));
	  }
	
	  array_unshift($arguments, $this);
	
	  return call_user_func_array($callable, $arguments);
	}

} // BaseCurrency
