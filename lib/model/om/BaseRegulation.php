<?php

/**
 * Base class that represents a row from the 'regulation' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseRegulation extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        RegulationPeer
	 */
	protected static $peer;

	/**
	 * The value for the creditor_firstname field.
	 * @var        string
	 */
	protected $creditor_firstname;

	/**
	 * The value for the creditor_lastname field.
	 * @var        string
	 */
	protected $creditor_lastname;

	/**
	 * The value for the contract_id field.
	 * @var        int
	 */
	protected $contract_id;

	/**
	 * The value for the contract_name field.
	 * @var        string
	 */
	protected $contract_name;

	/**
	 * The value for the settlement_year field.
	 * @var        string
	 */
	protected $settlement_year;

	/**
	 * The value for the start_balance field.
	 * @var        string
	 */
	protected $start_balance;

	/**
	 * The value for the contract_activated_at field.
	 * @var        string
	 */
	protected $contract_activated_at;

	/**
	 * The value for the contract_balance field.
	 * @var        string
	 */
	protected $contract_balance;

	/**
	 * The value for the regulation field.
	 * @var        string
	 */
	protected $regulation;

	/**
	 * The value for the paid field.
	 * @var        string
	 */
	protected $paid;

	/**
	 * The value for the paid_for_current_year field.
	 * @var        string
	 */
	protected $paid_for_current_year;

	/**
	 * The value for the capitalized field.
	 * @var        string
	 */
	protected $capitalized;

	/**
	 * The value for the teoretically_to_pay_in_current_year field.
	 * @var        string
	 */
	protected $teoretically_to_pay_in_current_year;

	/**
	 * The value for the end_balance field.
	 * @var        string
	 */
	protected $end_balance;

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
	
	const PEER = 'RegulationPeer';

	/**
	 * Get the [creditor_firstname] column value.
	 * 
	 * @return     string
	 */
	public function getCreditorFirstname()
	{
		return $this->creditor_firstname;
	}

	/**
	 * Get the [creditor_lastname] column value.
	 * 
	 * @return     string
	 */
	public function getCreditorLastname()
	{
		return $this->creditor_lastname;
	}

	/**
	 * Get the [contract_id] column value.
	 * 
	 * @return     int
	 */
	public function getContractId()
	{
		return $this->contract_id;
	}

	/**
	 * Get the [contract_name] column value.
	 * 
	 * @return     string
	 */
	public function getContractName()
	{
		return $this->contract_name;
	}

	/**
	 * Get the [settlement_year] column value.
	 * 
	 * @return     string
	 */
	public function getSettlementYear()
	{
		return $this->settlement_year;
	}

	/**
	 * Get the [start_balance] column value.
	 * 
	 * @return     string
	 */
	public function getStartBalance()
	{
		return $this->start_balance;
	}

	/**
	 * Get the [optionally formatted] temporal [contract_activated_at] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getContractActivatedAt($format = 'Y-m-d')
	{
		if ($this->contract_activated_at === null) {
			return null;
		}



		try {
			$dt = new DateTime($this->contract_activated_at);
		} catch (Exception $x) {
			throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->contract_activated_at, true), $x);
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
	 * Get the [contract_balance] column value.
	 * 
	 * @return     string
	 */
	public function getContractBalance()
	{
		return $this->contract_balance;
	}

	/**
	 * Get the [regulation] column value.
	 * 
	 * @return     string
	 */
	public function getRequlation()
	{
		return $this->regulation;
	}

	/**
	 * Get the [paid] column value.
	 * 
	 * @return     string
	 */
	public function getPid()
	{
		return $this->paid;
	}

	/**
	 * Get the [paid_for_current_year] column value.
	 * 
	 * @return     string
	 */
	public function getPaidForCurrentYear()
	{
		return $this->paid_for_current_year;
	}

	/**
	 * Get the [capitalized] column value.
	 * 
	 * @return     string
	 */
	public function getCapitalized()
	{
		return $this->capitalized;
	}

	/**
	 * Get the [teoretically_to_pay_in_current_year] column value.
	 * 
	 * @return     string
	 */
	public function getTeoreticallyToPayInCurrentYear()
	{
		return $this->teoretically_to_pay_in_current_year;
	}

	/**
	 * Get the [end_balance] column value.
	 * 
	 * @return     string
	 */
	public function getEndBalance()
	{
		return $this->end_balance;
	}

	/**
	 * Set the value of [creditor_firstname] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setCreditorFirstname($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->creditor_firstname !== $v) {
			$this->creditor_firstname = $v;
			$this->modifiedColumns[] = RegulationPeer::CREDITOR_FIRSTNAME;
		}

		return $this;
	} // setCreditorFirstname()

	/**
	 * Set the value of [creditor_lastname] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setCreditorLastname($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->creditor_lastname !== $v) {
			$this->creditor_lastname = $v;
			$this->modifiedColumns[] = RegulationPeer::CREDITOR_LASTNAME;
		}

		return $this;
	} // setCreditorLastname()

	/**
	 * Set the value of [contract_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setContractId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->contract_id !== $v) {
			$this->contract_id = $v;
			$this->modifiedColumns[] = RegulationPeer::CONTRACT_ID;
		}

		return $this;
	} // setContractId()

	/**
	 * Set the value of [contract_name] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setContractName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->contract_name !== $v) {
			$this->contract_name = $v;
			$this->modifiedColumns[] = RegulationPeer::CONTRACT_NAME;
		}

		return $this;
	} // setContractName()

	/**
	 * Set the value of [settlement_year] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setSettlementYear($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->settlement_year !== $v) {
			$this->settlement_year = $v;
			$this->modifiedColumns[] = RegulationPeer::SETTLEMENT_YEAR;
		}

		return $this;
	} // setSettlementYear()

	/**
	 * Set the value of [start_balance] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setStartBalance($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->start_balance !== $v) {
			$this->start_balance = $v;
			$this->modifiedColumns[] = RegulationPeer::START_BALANCE;
		}

		return $this;
	} // setStartBalance()

	/**
	 * Sets the value of [contract_activated_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setContractActivatedAt($v)
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

		if ( $this->contract_activated_at !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->contract_activated_at !== null && $tmpDt = new DateTime($this->contract_activated_at)) ? $tmpDt->format('Y-m-d') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->contract_activated_at = ($dt ? $dt->format('Y-m-d') : null);
				$this->modifiedColumns[] = RegulationPeer::CONTRACT_ACTIVATED_AT;
			}
		} // if either are not null

		return $this;
	} // setContractActivatedAt()

	/**
	 * Set the value of [contract_balance] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setContractBalance($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->contract_balance !== $v) {
			$this->contract_balance = $v;
			$this->modifiedColumns[] = RegulationPeer::CONTRACT_BALANCE;
		}

		return $this;
	} // setContractBalance()

	/**
	 * Set the value of [regulation] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setRequlation($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->regulation !== $v) {
			$this->regulation = $v;
			$this->modifiedColumns[] = RegulationPeer::REGULATION;
		}

		return $this;
	} // setRequlation()

	/**
	 * Set the value of [paid] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setPid($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->paid !== $v) {
			$this->paid = $v;
			$this->modifiedColumns[] = RegulationPeer::PAID;
		}

		return $this;
	} // setPid()

	/**
	 * Set the value of [paid_for_current_year] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setPaidForCurrentYear($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->paid_for_current_year !== $v) {
			$this->paid_for_current_year = $v;
			$this->modifiedColumns[] = RegulationPeer::PAID_FOR_CURRENT_YEAR;
		}

		return $this;
	} // setPaidForCurrentYear()

	/**
	 * Set the value of [capitalized] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setCapitalized($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->capitalized !== $v) {
			$this->capitalized = $v;
			$this->modifiedColumns[] = RegulationPeer::CAPITALIZED;
		}

		return $this;
	} // setCapitalized()

	/**
	 * Set the value of [teoretically_to_pay_in_current_year] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setTeoreticallyToPayInCurrentYear($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->teoretically_to_pay_in_current_year !== $v) {
			$this->teoretically_to_pay_in_current_year = $v;
			$this->modifiedColumns[] = RegulationPeer::TEORETICALLY_TO_PAY_IN_CURRENT_YEAR;
		}

		return $this;
	} // setTeoreticallyToPayInCurrentYear()

	/**
	 * Set the value of [end_balance] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setEndBalance($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->end_balance !== $v) {
			$this->end_balance = $v;
			$this->modifiedColumns[] = RegulationPeer::END_BALANCE;
		}

		return $this;
	} // setEndBalance()

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

			$this->creditor_firstname = ($row[$startcol + 0] !== null) ? (string) $row[$startcol + 0] : null;
			$this->creditor_lastname = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->contract_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->contract_name = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->settlement_year = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->start_balance = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->contract_activated_at = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->contract_balance = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->regulation = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->paid = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->paid_for_current_year = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->capitalized = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->teoretically_to_pay_in_current_year = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
			$this->end_balance = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 14; // 14 = RegulationPeer::NUM_COLUMNS - RegulationPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Regulation object", $e);
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
			$con = Propel::getConnection(RegulationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = RegulationPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

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
			$con = Propel::getConnection(RegulationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseRegulation:delete:pre') as $callable)
			{
			  if (call_user_func($callable, $this, $con))
			  {
			    $con->commit();
			
			    return;
			  }
			}

			if ($ret) {
				RegulationPeer::doDelete($this, $con);
				$this->postDelete($con);
				// symfony_behaviors behavior
				foreach (sfMixer::getCallables('BaseRegulation:delete:post') as $callable)
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
			$con = Propel::getConnection(RegulationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseRegulation:save:pre') as $callable)
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
				foreach (sfMixer::getCallables('BaseRegulation:save:post') as $callable)
				{
				  call_user_func($callable, $this, $con, $affectedRows);
				}

				RegulationPeer::addInstanceToPool($this);
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
					$pk = RegulationPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNew(false);
				} else {
					$affectedRows += RegulationPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
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


			if (($retval = RegulationPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
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
		$pos = RegulationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getCreditorFirstname();
				break;
			case 1:
				return $this->getCreditorLastname();
				break;
			case 2:
				return $this->getContractId();
				break;
			case 3:
				return $this->getContractName();
				break;
			case 4:
				return $this->getSettlementYear();
				break;
			case 5:
				return $this->getStartBalance();
				break;
			case 6:
				return $this->getContractActivatedAt();
				break;
			case 7:
				return $this->getContractBalance();
				break;
			case 8:
				return $this->getRequlation();
				break;
			case 9:
				return $this->getPid();
				break;
			case 10:
				return $this->getPaidForCurrentYear();
				break;
			case 11:
				return $this->getCapitalized();
				break;
			case 12:
				return $this->getTeoreticallyToPayInCurrentYear();
				break;
			case 13:
				return $this->getEndBalance();
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
		$keys = RegulationPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getCreditorFirstname(),
			$keys[1] => $this->getCreditorLastname(),
			$keys[2] => $this->getContractId(),
			$keys[3] => $this->getContractName(),
			$keys[4] => $this->getSettlementYear(),
			$keys[5] => $this->getStartBalance(),
			$keys[6] => $this->getContractActivatedAt(),
			$keys[7] => $this->getContractBalance(),
			$keys[8] => $this->getRequlation(),
			$keys[9] => $this->getPid(),
			$keys[10] => $this->getPaidForCurrentYear(),
			$keys[11] => $this->getCapitalized(),
			$keys[12] => $this->getTeoreticallyToPayInCurrentYear(),
			$keys[13] => $this->getEndBalance(),
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
		$pos = RegulationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setCreditorFirstname($value);
				break;
			case 1:
				$this->setCreditorLastname($value);
				break;
			case 2:
				$this->setContractId($value);
				break;
			case 3:
				$this->setContractName($value);
				break;
			case 4:
				$this->setSettlementYear($value);
				break;
			case 5:
				$this->setStartBalance($value);
				break;
			case 6:
				$this->setContractActivatedAt($value);
				break;
			case 7:
				$this->setContractBalance($value);
				break;
			case 8:
				$this->setRequlation($value);
				break;
			case 9:
				$this->setPid($value);
				break;
			case 10:
				$this->setPaidForCurrentYear($value);
				break;
			case 11:
				$this->setCapitalized($value);
				break;
			case 12:
				$this->setTeoreticallyToPayInCurrentYear($value);
				break;
			case 13:
				$this->setEndBalance($value);
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
		$keys = RegulationPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setCreditorFirstname($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setCreditorLastname($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setContractId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setContractName($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setSettlementYear($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setStartBalance($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setContractActivatedAt($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setContractBalance($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setRequlation($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setPid($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setPaidForCurrentYear($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setCapitalized($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setTeoreticallyToPayInCurrentYear($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setEndBalance($arr[$keys[13]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(RegulationPeer::DATABASE_NAME);

		if ($this->isColumnModified(RegulationPeer::CREDITOR_FIRSTNAME)) $criteria->add(RegulationPeer::CREDITOR_FIRSTNAME, $this->creditor_firstname);
		if ($this->isColumnModified(RegulationPeer::CREDITOR_LASTNAME)) $criteria->add(RegulationPeer::CREDITOR_LASTNAME, $this->creditor_lastname);
		if ($this->isColumnModified(RegulationPeer::CONTRACT_ID)) $criteria->add(RegulationPeer::CONTRACT_ID, $this->contract_id);
		if ($this->isColumnModified(RegulationPeer::CONTRACT_NAME)) $criteria->add(RegulationPeer::CONTRACT_NAME, $this->contract_name);
		if ($this->isColumnModified(RegulationPeer::SETTLEMENT_YEAR)) $criteria->add(RegulationPeer::SETTLEMENT_YEAR, $this->settlement_year);
		if ($this->isColumnModified(RegulationPeer::START_BALANCE)) $criteria->add(RegulationPeer::START_BALANCE, $this->start_balance);
		if ($this->isColumnModified(RegulationPeer::CONTRACT_ACTIVATED_AT)) $criteria->add(RegulationPeer::CONTRACT_ACTIVATED_AT, $this->contract_activated_at);
		if ($this->isColumnModified(RegulationPeer::CONTRACT_BALANCE)) $criteria->add(RegulationPeer::CONTRACT_BALANCE, $this->contract_balance);
		if ($this->isColumnModified(RegulationPeer::REGULATION)) $criteria->add(RegulationPeer::REGULATION, $this->regulation);
		if ($this->isColumnModified(RegulationPeer::PAID)) $criteria->add(RegulationPeer::PAID, $this->paid);
		if ($this->isColumnModified(RegulationPeer::PAID_FOR_CURRENT_YEAR)) $criteria->add(RegulationPeer::PAID_FOR_CURRENT_YEAR, $this->paid_for_current_year);
		if ($this->isColumnModified(RegulationPeer::CAPITALIZED)) $criteria->add(RegulationPeer::CAPITALIZED, $this->capitalized);
		if ($this->isColumnModified(RegulationPeer::TEORETICALLY_TO_PAY_IN_CURRENT_YEAR)) $criteria->add(RegulationPeer::TEORETICALLY_TO_PAY_IN_CURRENT_YEAR, $this->teoretically_to_pay_in_current_year);
		if ($this->isColumnModified(RegulationPeer::END_BALANCE)) $criteria->add(RegulationPeer::END_BALANCE, $this->end_balance);

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
		$criteria = new Criteria(RegulationPeer::DATABASE_NAME);

		$criteria->add(RegulationPeer::CONTRACT_ID, $this->contract_id);
		$criteria->add(RegulationPeer::SETTLEMENT_YEAR, $this->settlement_year);

		return $criteria;
	}

	/**
	 * Returns the composite primary key for this object.
	 * The array elements will be in same order as specified in XML.
	 * @return     array
	 */
	public function getPrimaryKey()
	{
		$pks = array();

		$pks[0] = $this->getContractId();

		$pks[1] = $this->getSettlementYear();

		return $pks;
	}

	/**
	 * Set the [composite] primary key.
	 *
	 * @param      array $keys The elements of the composite key (order must match the order in XML file).
	 * @return     void
	 */
	public function setPrimaryKey($keys)
	{

		$this->setContractId($keys[0]);

		$this->setSettlementYear($keys[1]);

	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of Regulation (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setCreditorFirstname($this->creditor_firstname);

		$copyObj->setCreditorLastname($this->creditor_lastname);

		$copyObj->setContractId($this->contract_id);

		$copyObj->setContractName($this->contract_name);

		$copyObj->setSettlementYear($this->settlement_year);

		$copyObj->setStartBalance($this->start_balance);

		$copyObj->setContractActivatedAt($this->contract_activated_at);

		$copyObj->setContractBalance($this->contract_balance);

		$copyObj->setRequlation($this->regulation);

		$copyObj->setPid($this->paid);

		$copyObj->setPaidForCurrentYear($this->paid_for_current_year);

		$copyObj->setCapitalized($this->capitalized);

		$copyObj->setTeoreticallyToPayInCurrentYear($this->teoretically_to_pay_in_current_year);

		$copyObj->setEndBalance($this->end_balance);


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
	 * @return     Regulation Clone of current object.
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
	 * @return     RegulationPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new RegulationPeer();
		}
		return self::$peer;
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
		} // if ($deep)

	}

	// symfony_behaviors behavior
	
	/**
	 * Calls methods defined via {@link sfMixer}.
	 */
	public function __call($method, $arguments)
	{
	  if (!$callable = sfMixer::getCallable('BaseRegulation:'.$method))
	  {
	    throw new sfException(sprintf('Call to undefined method BaseRegulation::%s', $method));
	  }
	
	  array_unshift($arguments, $this);
	
	  return call_user_func_array($callable, $arguments);
	}

} // BaseRegulation
