<?php

/**
 * Base class that represents a row from the 'creditor' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseCreditor extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        CreditorPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the creditor_type_code field.
	 * @var        string
	 */
	protected $creditor_type_code;

	/**
	 * The value for the identification_number field.
	 * @var        string
	 */
	protected $identification_number;

	/**
	 * The value for the firstname field.
	 * @var        string
	 */
	protected $firstname;

	/**
	 * The value for the lastname field.
	 * @var        string
	 */
	protected $lastname;

	/**
	 * The value for the email field.
	 * @var        string
	 */
	protected $email;

	/**
	 * The value for the phone field.
	 * @var        string
	 */
	protected $phone;

	/**
	 * The value for the bank_account field.
	 * @var        string
	 */
	protected $bank_account;

	/**
	 * The value for the city field.
	 * @var        string
	 */
	protected $city;

	/**
	 * The value for the street field.
	 * @var        string
	 */
	protected $street;

	/**
	 * The value for the zip field.
	 * @var        string
	 */
	protected $zip;

	/**
	 * The value for the note field.
	 * @var        string
	 */
	protected $note;

	/**
	 * The value for the birth_date field.
	 * @var        string
	 */
	protected $birth_date;

	/**
	 * @var        array Contract[] Collection to store aggregation of Contract objects.
	 */
	protected $collContracts;

	/**
	 * @var        Criteria The criteria used to select the current contents of collContracts.
	 */
	private $lastContractCriteria = null;

	/**
	 * @var        array Unpaid[] Collection to store aggregation of Unpaid objects.
	 */
	protected $collUnpaids;

	/**
	 * @var        Criteria The criteria used to select the current contents of collUnpaids.
	 */
	private $lastUnpaidCriteria = null;

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
	
	const PEER = 'CreditorPeer';

	/**
	 * Get the [id] column value.
	 * 
	 * @return     int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get the [creditor_type_code] column value.
	 * 
	 * @return     string
	 */
	public function getCreditorTypeCode()
	{
		return $this->creditor_type_code;
	}

	/**
	 * Get the [identification_number] column value.
	 * 
	 * @return     string
	 */
	public function getIdentificationNumber()
	{
		return $this->identification_number;
	}

	/**
	 * Get the [firstname] column value.
	 * 
	 * @return     string
	 */
	public function getFirstname()
	{
		return $this->firstname;
	}

	/**
	 * Get the [lastname] column value.
	 * 
	 * @return     string
	 */
	public function getLastname()
	{
		return $this->lastname;
	}

	/**
	 * Get the [email] column value.
	 * 
	 * @return     string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Get the [phone] column value.
	 * 
	 * @return     string
	 */
	public function getPhone()
	{
		return $this->phone;
	}

	/**
	 * Get the [bank_account] column value.
	 * 
	 * @return     string
	 */
	public function getBankAccount()
	{
		return $this->bank_account;
	}

	/**
	 * Get the [city] column value.
	 * 
	 * @return     string
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * Get the [street] column value.
	 * 
	 * @return     string
	 */
	public function getStreet()
	{
		return $this->street;
	}

	/**
	 * Get the [zip] column value.
	 * 
	 * @return     string
	 */
	public function getZip()
	{
		return $this->zip;
	}

	/**
	 * Get the [note] column value.
	 * 
	 * @return     string
	 */
	public function getNote()
	{
		return $this->note;
	}

	/**
	 * Get the [optionally formatted] temporal [birth_date] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getBirthDate($format = 'Y-m-d H:i:s')
	{
		if ($this->birth_date === null) {
			return null;
		}



		try {
			$dt = new DateTime($this->birth_date);
		} catch (Exception $x) {
			throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->birth_date, true), $x);
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     Creditor The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = CreditorPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [creditor_type_code] column.
	 * 
	 * @param      string $v new value
	 * @return     Creditor The current object (for fluent API support)
	 */
	public function setCreditorTypeCode($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->creditor_type_code !== $v) {
			$this->creditor_type_code = $v;
			$this->modifiedColumns[] = CreditorPeer::CREDITOR_TYPE_CODE;
		}

		return $this;
	} // setCreditorTypeCode()

	/**
	 * Set the value of [identification_number] column.
	 * 
	 * @param      string $v new value
	 * @return     Creditor The current object (for fluent API support)
	 */
	public function setIdentificationNumber($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->identification_number !== $v) {
			$this->identification_number = $v;
			$this->modifiedColumns[] = CreditorPeer::IDENTIFICATION_NUMBER;
		}

		return $this;
	} // setIdentificationNumber()

	/**
	 * Set the value of [firstname] column.
	 * 
	 * @param      string $v new value
	 * @return     Creditor The current object (for fluent API support)
	 */
	public function setFirstname($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->firstname !== $v) {
			$this->firstname = $v;
			$this->modifiedColumns[] = CreditorPeer::FIRSTNAME;
		}

		return $this;
	} // setFirstname()

	/**
	 * Set the value of [lastname] column.
	 * 
	 * @param      string $v new value
	 * @return     Creditor The current object (for fluent API support)
	 */
	public function setLastname($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->lastname !== $v) {
			$this->lastname = $v;
			$this->modifiedColumns[] = CreditorPeer::LASTNAME;
		}

		return $this;
	} // setLastname()

	/**
	 * Set the value of [email] column.
	 * 
	 * @param      string $v new value
	 * @return     Creditor The current object (for fluent API support)
	 */
	public function setEmail($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->email !== $v) {
			$this->email = $v;
			$this->modifiedColumns[] = CreditorPeer::EMAIL;
		}

		return $this;
	} // setEmail()

	/**
	 * Set the value of [phone] column.
	 * 
	 * @param      string $v new value
	 * @return     Creditor The current object (for fluent API support)
	 */
	public function setPhone($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->phone !== $v) {
			$this->phone = $v;
			$this->modifiedColumns[] = CreditorPeer::PHONE;
		}

		return $this;
	} // setPhone()

	/**
	 * Set the value of [bank_account] column.
	 * 
	 * @param      string $v new value
	 * @return     Creditor The current object (for fluent API support)
	 */
	public function setBankAccount($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->bank_account !== $v) {
			$this->bank_account = $v;
			$this->modifiedColumns[] = CreditorPeer::BANK_ACCOUNT;
		}

		return $this;
	} // setBankAccount()

	/**
	 * Set the value of [city] column.
	 * 
	 * @param      string $v new value
	 * @return     Creditor The current object (for fluent API support)
	 */
	public function setCity($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->city !== $v) {
			$this->city = $v;
			$this->modifiedColumns[] = CreditorPeer::CITY;
		}

		return $this;
	} // setCity()

	/**
	 * Set the value of [street] column.
	 * 
	 * @param      string $v new value
	 * @return     Creditor The current object (for fluent API support)
	 */
	public function setStreet($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->street !== $v) {
			$this->street = $v;
			$this->modifiedColumns[] = CreditorPeer::STREET;
		}

		return $this;
	} // setStreet()

	/**
	 * Set the value of [zip] column.
	 * 
	 * @param      string $v new value
	 * @return     Creditor The current object (for fluent API support)
	 */
	public function setZip($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->zip !== $v) {
			$this->zip = $v;
			$this->modifiedColumns[] = CreditorPeer::ZIP;
		}

		return $this;
	} // setZip()

	/**
	 * Set the value of [note] column.
	 * 
	 * @param      string $v new value
	 * @return     Creditor The current object (for fluent API support)
	 */
	public function setNote($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->note !== $v) {
			$this->note = $v;
			$this->modifiedColumns[] = CreditorPeer::NOTE;
		}

		return $this;
	} // setNote()

	/**
	 * Sets the value of [birth_date] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Creditor The current object (for fluent API support)
	 */
	public function setBirthDate($v)
	{
		// we treat '' as NULL for temporal objects because DateTime('') == DateTime('now')
		// -- which is unexpected, to say the least.
		if ($v === null || $v === '') {
			$dt = null;
		} elseif ($v instanceof DateTime) {
			$dt = $v;
		} else {
			// some string/numeric value passed; we normalize that so that we can
			// validate it.
			try {
				if (is_numeric($v)) { // if it's a unix timestamp
					$dt = new DateTime('@'.$v, new DateTimeZone('UTC'));
					// We have to explicitly specify and then change the time zone because of a
					// DateTime bug: http://bugs.php.net/bug.php?id=43003
					$dt->setTimeZone(new DateTimeZone(date_default_timezone_get()));
				} else {
					$dt = new DateTime($v);
				}
			} catch (Exception $x) {
				throw new PropelException('Error parsing date/time value: ' . var_export($v, true), $x);
			}
		}

		if ( $this->birth_date !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->birth_date !== null && $tmpDt = new DateTime($this->birth_date)) ? $tmpDt->format('Y-m-d\\TH:i:sO') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d\\TH:i:sO') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->birth_date = ($dt ? $dt->format('Y-m-d\\TH:i:sO') : null);
				$this->modifiedColumns[] = CreditorPeer::BIRTH_DATE;
			}
		} // if either are not null

		return $this;
	} // setBirthDate()

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

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->creditor_type_code = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->identification_number = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->firstname = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->lastname = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->email = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->phone = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->bank_account = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->city = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->street = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->zip = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->note = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->birth_date = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 13; // 13 = CreditorPeer::NUM_COLUMNS - CreditorPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Creditor object", $e);
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
			$con = Propel::getConnection(CreditorPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = CreditorPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collContracts = null;
			$this->lastContractCriteria = null;

			$this->collUnpaids = null;
			$this->lastUnpaidCriteria = null;

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
			$con = Propel::getConnection(CreditorPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseCreditor:delete:pre') as $callable)
			{
			  if (call_user_func($callable, $this, $con))
			  {
			    $con->commit();
			
			    return;
			  }
			}

			if ($ret) {
				CreditorPeer::doDelete($this, $con);
				$this->postDelete($con);
				// symfony_behaviors behavior
				foreach (sfMixer::getCallables('BaseCreditor:delete:post') as $callable)
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
			$con = Propel::getConnection(CreditorPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseCreditor:save:pre') as $callable)
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
				foreach (sfMixer::getCallables('BaseCreditor:save:post') as $callable)
				{
				  call_user_func($callable, $this, $con, $affectedRows);
				}

				CreditorPeer::addInstanceToPool($this);
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

			if ($this->isNew() ) {
				$this->modifiedColumns[] = CreditorPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = CreditorPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += CreditorPeer::doUpdate($this, $con);
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

			if ($this->collUnpaids !== null) {
				foreach ($this->collUnpaids as $referrerFK) {
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


			if (($retval = CreditorPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collContracts !== null) {
					foreach ($this->collContracts as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collUnpaids !== null) {
					foreach ($this->collUnpaids as $referrerFK) {
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
		$pos = CreditorPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getId();
				break;
			case 1:
				return $this->getCreditorTypeCode();
				break;
			case 2:
				return $this->getIdentificationNumber();
				break;
			case 3:
				return $this->getFirstname();
				break;
			case 4:
				return $this->getLastname();
				break;
			case 5:
				return $this->getEmail();
				break;
			case 6:
				return $this->getPhone();
				break;
			case 7:
				return $this->getBankAccount();
				break;
			case 8:
				return $this->getCity();
				break;
			case 9:
				return $this->getStreet();
				break;
			case 10:
				return $this->getZip();
				break;
			case 11:
				return $this->getNote();
				break;
			case 12:
				return $this->getBirthDate();
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
		$keys = CreditorPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getCreditorTypeCode(),
			$keys[2] => $this->getIdentificationNumber(),
			$keys[3] => $this->getFirstname(),
			$keys[4] => $this->getLastname(),
			$keys[5] => $this->getEmail(),
			$keys[6] => $this->getPhone(),
			$keys[7] => $this->getBankAccount(),
			$keys[8] => $this->getCity(),
			$keys[9] => $this->getStreet(),
			$keys[10] => $this->getZip(),
			$keys[11] => $this->getNote(),
			$keys[12] => $this->getBirthDate(),
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
		$pos = CreditorPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setId($value);
				break;
			case 1:
				$this->setCreditorTypeCode($value);
				break;
			case 2:
				$this->setIdentificationNumber($value);
				break;
			case 3:
				$this->setFirstname($value);
				break;
			case 4:
				$this->setLastname($value);
				break;
			case 5:
				$this->setEmail($value);
				break;
			case 6:
				$this->setPhone($value);
				break;
			case 7:
				$this->setBankAccount($value);
				break;
			case 8:
				$this->setCity($value);
				break;
			case 9:
				$this->setStreet($value);
				break;
			case 10:
				$this->setZip($value);
				break;
			case 11:
				$this->setNote($value);
				break;
			case 12:
				$this->setBirthDate($value);
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
		$keys = CreditorPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setCreditorTypeCode($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setIdentificationNumber($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setFirstname($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setLastname($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setEmail($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setPhone($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setBankAccount($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setCity($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setStreet($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setZip($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setNote($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setBirthDate($arr[$keys[12]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(CreditorPeer::DATABASE_NAME);

		if ($this->isColumnModified(CreditorPeer::ID)) $criteria->add(CreditorPeer::ID, $this->id);
		if ($this->isColumnModified(CreditorPeer::CREDITOR_TYPE_CODE)) $criteria->add(CreditorPeer::CREDITOR_TYPE_CODE, $this->creditor_type_code);
		if ($this->isColumnModified(CreditorPeer::IDENTIFICATION_NUMBER)) $criteria->add(CreditorPeer::IDENTIFICATION_NUMBER, $this->identification_number);
		if ($this->isColumnModified(CreditorPeer::FIRSTNAME)) $criteria->add(CreditorPeer::FIRSTNAME, $this->firstname);
		if ($this->isColumnModified(CreditorPeer::LASTNAME)) $criteria->add(CreditorPeer::LASTNAME, $this->lastname);
		if ($this->isColumnModified(CreditorPeer::EMAIL)) $criteria->add(CreditorPeer::EMAIL, $this->email);
		if ($this->isColumnModified(CreditorPeer::PHONE)) $criteria->add(CreditorPeer::PHONE, $this->phone);
		if ($this->isColumnModified(CreditorPeer::BANK_ACCOUNT)) $criteria->add(CreditorPeer::BANK_ACCOUNT, $this->bank_account);
		if ($this->isColumnModified(CreditorPeer::CITY)) $criteria->add(CreditorPeer::CITY, $this->city);
		if ($this->isColumnModified(CreditorPeer::STREET)) $criteria->add(CreditorPeer::STREET, $this->street);
		if ($this->isColumnModified(CreditorPeer::ZIP)) $criteria->add(CreditorPeer::ZIP, $this->zip);
		if ($this->isColumnModified(CreditorPeer::NOTE)) $criteria->add(CreditorPeer::NOTE, $this->note);
		if ($this->isColumnModified(CreditorPeer::BIRTH_DATE)) $criteria->add(CreditorPeer::BIRTH_DATE, $this->birth_date);

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
		$criteria = new Criteria(CreditorPeer::DATABASE_NAME);

		$criteria->add(CreditorPeer::ID, $this->id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of Creditor (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setCreditorTypeCode($this->creditor_type_code);

		$copyObj->setIdentificationNumber($this->identification_number);

		$copyObj->setFirstname($this->firstname);

		$copyObj->setLastname($this->lastname);

		$copyObj->setEmail($this->email);

		$copyObj->setPhone($this->phone);

		$copyObj->setBankAccount($this->bank_account);

		$copyObj->setCity($this->city);

		$copyObj->setStreet($this->street);

		$copyObj->setZip($this->zip);

		$copyObj->setNote($this->note);

		$copyObj->setBirthDate($this->birth_date);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getContracts() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addContract($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getUnpaids() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addUnpaid($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setId(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     Creditor Clone of current object.
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
	 * @return     CreditorPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new CreditorPeer();
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
	 * Otherwise if this Creditor has previously been saved, it will retrieve
	 * related Contracts from storage. If this Creditor is new, it will return
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
			$criteria = new Criteria(CreditorPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collContracts === null) {
			if ($this->isNew()) {
			   $this->collContracts = array();
			} else {

				$criteria->add(ContractPeer::CREDITOR_ID, $this->id);

				ContractPeer::addSelectColumns($criteria);
				$this->collContracts = ContractPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(ContractPeer::CREDITOR_ID, $this->id);

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
			$criteria = new Criteria(CreditorPeer::DATABASE_NAME);
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

				$criteria->add(ContractPeer::CREDITOR_ID, $this->id);

				$count = ContractPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(ContractPeer::CREDITOR_ID, $this->id);

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
			$l->setCreditor($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Creditor is new, it will return
	 * an empty collection; or if this Creditor has previously
	 * been saved, it will retrieve related Contracts from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Creditor.
	 */
	public function getContractsJoinCurrency($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CreditorPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collContracts === null) {
			if ($this->isNew()) {
				$this->collContracts = array();
			} else {

				$criteria->add(ContractPeer::CREDITOR_ID, $this->id);

				$this->collContracts = ContractPeer::doSelectJoinCurrency($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(ContractPeer::CREDITOR_ID, $this->id);

			if (!isset($this->lastContractCriteria) || !$this->lastContractCriteria->equals($criteria)) {
				$this->collContracts = ContractPeer::doSelectJoinCurrency($criteria, $con, $join_behavior);
			}
		}
		$this->lastContractCriteria = $criteria;

		return $this->collContracts;
	}

	/**
	 * Clears out the collUnpaids collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addUnpaids()
	 */
	public function clearUnpaids()
	{
		$this->collUnpaids = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collUnpaids collection (array).
	 *
	 * By default this just sets the collUnpaids collection to an empty array (like clearcollUnpaids());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initUnpaids()
	{
		$this->collUnpaids = array();
	}

	/**
	 * Gets an array of Unpaid objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Creditor has previously been saved, it will retrieve
	 * related Unpaids from storage. If this Creditor is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Unpaid[]
	 * @throws     PropelException
	 */
	public function getUnpaids($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CreditorPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collUnpaids === null) {
			if ($this->isNew()) {
			   $this->collUnpaids = array();
			} else {

				$criteria->add(UnpaidPeer::CREDITOR_ID, $this->id);

				UnpaidPeer::addSelectColumns($criteria);
				$this->collUnpaids = UnpaidPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(UnpaidPeer::CREDITOR_ID, $this->id);

				UnpaidPeer::addSelectColumns($criteria);
				if (!isset($this->lastUnpaidCriteria) || !$this->lastUnpaidCriteria->equals($criteria)) {
					$this->collUnpaids = UnpaidPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastUnpaidCriteria = $criteria;
		return $this->collUnpaids;
	}

	/**
	 * Returns the number of related Unpaid objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Unpaid objects.
	 * @throws     PropelException
	 */
	public function countUnpaids(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CreditorPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collUnpaids === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(UnpaidPeer::CREDITOR_ID, $this->id);

				$count = UnpaidPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(UnpaidPeer::CREDITOR_ID, $this->id);

				if (!isset($this->lastUnpaidCriteria) || !$this->lastUnpaidCriteria->equals($criteria)) {
					$count = UnpaidPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collUnpaids);
				}
			} else {
				$count = count($this->collUnpaids);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Unpaid object to this object
	 * through the Unpaid foreign key attribute.
	 *
	 * @param      Unpaid $l Unpaid
	 * @return     void
	 * @throws     PropelException
	 */
	public function addUnpaid(Unpaid $l)
	{
		if ($this->collUnpaids === null) {
			$this->initUnpaids();
		}
		if (!in_array($l, $this->collUnpaids, true)) { // only add it if the **same** object is not already associated
			array_push($this->collUnpaids, $l);
			$l->setCreditor($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Creditor is new, it will return
	 * an empty collection; or if this Creditor has previously
	 * been saved, it will retrieve related Unpaids from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Creditor.
	 */
	public function getUnpaidsJoinContract($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(CreditorPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collUnpaids === null) {
			if ($this->isNew()) {
				$this->collUnpaids = array();
			} else {

				$criteria->add(UnpaidPeer::CREDITOR_ID, $this->id);

				$this->collUnpaids = UnpaidPeer::doSelectJoinContract($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(UnpaidPeer::CREDITOR_ID, $this->id);

			if (!isset($this->lastUnpaidCriteria) || !$this->lastUnpaidCriteria->equals($criteria)) {
				$this->collUnpaids = UnpaidPeer::doSelectJoinContract($criteria, $con, $join_behavior);
			}
		}
		$this->lastUnpaidCriteria = $criteria;

		return $this->collUnpaids;
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
			if ($this->collUnpaids) {
				foreach ((array) $this->collUnpaids as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collContracts = null;
		$this->collUnpaids = null;
	}

	// symfony_behaviors behavior
	
	/**
	 * Calls methods defined via {@link sfMixer}.
	 */
	public function __call($method, $arguments)
	{
	  if (!$callable = sfMixer::getCallable('BaseCreditor:'.$method))
	  {
	    throw new sfException(sprintf('Call to undefined method BaseCreditor::%s', $method));
	  }
	
	  array_unshift($arguments, $this);
	
	  return call_user_func_array($callable, $arguments);
	}

} // BaseCreditor
