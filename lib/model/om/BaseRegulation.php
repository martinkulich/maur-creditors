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
	 * The value for the id field.
	 * @var        string
	 */
	protected $id;

	/**
	 * The value for the creditor_fullname field.
	 * @var        string
	 */
	protected $creditor_fullname;

	/**
	 * The value for the creditor_id field.
	 * @var        int
	 */
	protected $creditor_id;

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
	 * The value for the regulation_year field.
	 * @var        string
	 */
	protected $regulation_year;

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
	 * The value for the unpaid field.
	 * @var        string
	 */
	protected $unpaid;

	/**
	 * The value for the unpaid_in_past field.
	 * @var        string
	 */
	protected $unpaid_in_past;

	/**
	 * The value for the end_balance field.
	 * @var        string
	 */
	protected $end_balance;

	/**
	 * @var        Contract
	 */
	protected $aContract;

	/**
	 * @var        Creditor
	 */
	protected $aCreditor;

	/**
	 * @var        RegulationYear
	 */
	protected $aRegulationYearRelatedByRegulationYear;

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
	 * Get the [id] column value.
	 * 
	 * @return     string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get the [creditor_fullname] column value.
	 * 
	 * @return     string
	 */
	public function getCreditorFirstname()
	{
		return $this->creditor_fullname;
	}

	/**
	 * Get the [creditor_id] column value.
	 * 
	 * @return     int
	 */
	public function getCreditorId()
	{
		return $this->creditor_id;
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
	 * Get the [regulation_year] column value.
	 * 
	 * @return     string
	 */
	public function getRegulationYear()
	{
		return $this->regulation_year;
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
	public function getPaid()
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
	 * Get the [unpaid] column value.
	 * 
	 * @return     string
	 */
	public function getUnpaid()
	{
		return $this->unpaid;
	}

	/**
	 * Get the [unpaid_in_past] column value.
	 * 
	 * @return     string
	 */
	public function getUnpaidInPast()
	{
		return $this->unpaid_in_past;
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
	 * Set the value of [id] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = RegulationPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [creditor_fullname] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setCreditorFirstname($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->creditor_fullname !== $v) {
			$this->creditor_fullname = $v;
			$this->modifiedColumns[] = RegulationPeer::CREDITOR_FULLNAME;
		}

		return $this;
	} // setCreditorFirstname()

	/**
	 * Set the value of [creditor_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setCreditorId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->creditor_id !== $v) {
			$this->creditor_id = $v;
			$this->modifiedColumns[] = RegulationPeer::CREDITOR_ID;
		}

		if ($this->aCreditor !== null && $this->aCreditor->getId() !== $v) {
			$this->aCreditor = null;
		}

		return $this;
	} // setCreditorId()

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

		if ($this->aContract !== null && $this->aContract->getId() !== $v) {
			$this->aContract = null;
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
	 * Set the value of [regulation_year] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setRegulationYear($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->regulation_year !== $v) {
			$this->regulation_year = $v;
			$this->modifiedColumns[] = RegulationPeer::REGULATION_YEAR;
		}

		if ($this->aRegulationYearRelatedByRegulationYear !== null && $this->aRegulationYearRelatedByRegulationYear->getId() !== $v) {
			$this->aRegulationYearRelatedByRegulationYear = null;
		}

		return $this;
	} // setRegulationYear()

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
	public function setPaid($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->paid !== $v) {
			$this->paid = $v;
			$this->modifiedColumns[] = RegulationPeer::PAID;
		}

		return $this;
	} // setPaid()

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
	 * Set the value of [unpaid] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setUnpaid($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->unpaid !== $v) {
			$this->unpaid = $v;
			$this->modifiedColumns[] = RegulationPeer::UNPAID;
		}

		return $this;
	} // setUnpaid()

	/**
	 * Set the value of [unpaid_in_past] column.
	 * 
	 * @param      string $v new value
	 * @return     Regulation The current object (for fluent API support)
	 */
	public function setUnpaidInPast($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->unpaid_in_past !== $v) {
			$this->unpaid_in_past = $v;
			$this->modifiedColumns[] = RegulationPeer::UNPAID_IN_PAST;
		}

		return $this;
	} // setUnpaidInPast()

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

			$this->id = ($row[$startcol + 0] !== null) ? (string) $row[$startcol + 0] : null;
			$this->creditor_fullname = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->creditor_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->contract_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->contract_name = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->regulation_year = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->start_balance = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->contract_activated_at = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->contract_balance = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->regulation = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->paid = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->paid_for_current_year = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->capitalized = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
			$this->unpaid = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
			$this->unpaid_in_past = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
			$this->end_balance = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 16; // 16 = RegulationPeer::NUM_COLUMNS - RegulationPeer::NUM_LAZY_LOAD_COLUMNS).

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

		if ($this->aCreditor !== null && $this->creditor_id !== $this->aCreditor->getId()) {
			$this->aCreditor = null;
		}
		if ($this->aContract !== null && $this->contract_id !== $this->aContract->getId()) {
			$this->aContract = null;
		}
		if ($this->aRegulationYearRelatedByRegulationYear !== null && $this->regulation_year !== $this->aRegulationYearRelatedByRegulationYear->getId()) {
			$this->aRegulationYearRelatedByRegulationYear = null;
		}
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

			$this->aContract = null;
			$this->aCreditor = null;
			$this->aRegulationYearRelatedByRegulationYear = null;
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

			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aContract !== null) {
				if ($this->aContract->isModified() || $this->aContract->isNew()) {
					$affectedRows += $this->aContract->save($con);
				}
				$this->setContract($this->aContract);
			}

			if ($this->aCreditor !== null) {
				if ($this->aCreditor->isModified() || $this->aCreditor->isNew()) {
					$affectedRows += $this->aCreditor->save($con);
				}
				$this->setCreditor($this->aCreditor);
			}

			if ($this->aRegulationYearRelatedByRegulationYear !== null) {
				if ($this->aRegulationYearRelatedByRegulationYear->isModified() || $this->aRegulationYearRelatedByRegulationYear->isNew()) {
					$affectedRows += $this->aRegulationYearRelatedByRegulationYear->save($con);
				}
				$this->setRegulationYearRelatedByRegulationYear($this->aRegulationYearRelatedByRegulationYear);
			}


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


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aContract !== null) {
				if (!$this->aContract->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aContract->getValidationFailures());
				}
			}

			if ($this->aCreditor !== null) {
				if (!$this->aCreditor->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aCreditor->getValidationFailures());
				}
			}

			if ($this->aRegulationYearRelatedByRegulationYear !== null) {
				if (!$this->aRegulationYearRelatedByRegulationYear->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aRegulationYearRelatedByRegulationYear->getValidationFailures());
				}
			}


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
				return $this->getId();
				break;
			case 1:
				return $this->getCreditorFirstname();
				break;
			case 2:
				return $this->getCreditorId();
				break;
			case 3:
				return $this->getContractId();
				break;
			case 4:
				return $this->getContractName();
				break;
			case 5:
				return $this->getRegulationYear();
				break;
			case 6:
				return $this->getStartBalance();
				break;
			case 7:
				return $this->getContractActivatedAt();
				break;
			case 8:
				return $this->getContractBalance();
				break;
			case 9:
				return $this->getRequlation();
				break;
			case 10:
				return $this->getPaid();
				break;
			case 11:
				return $this->getPaidForCurrentYear();
				break;
			case 12:
				return $this->getCapitalized();
				break;
			case 13:
				return $this->getUnpaid();
				break;
			case 14:
				return $this->getUnpaidInPast();
				break;
			case 15:
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
			$keys[0] => $this->getId(),
			$keys[1] => $this->getCreditorFirstname(),
			$keys[2] => $this->getCreditorId(),
			$keys[3] => $this->getContractId(),
			$keys[4] => $this->getContractName(),
			$keys[5] => $this->getRegulationYear(),
			$keys[6] => $this->getStartBalance(),
			$keys[7] => $this->getContractActivatedAt(),
			$keys[8] => $this->getContractBalance(),
			$keys[9] => $this->getRequlation(),
			$keys[10] => $this->getPaid(),
			$keys[11] => $this->getPaidForCurrentYear(),
			$keys[12] => $this->getCapitalized(),
			$keys[13] => $this->getUnpaid(),
			$keys[14] => $this->getUnpaidInPast(),
			$keys[15] => $this->getEndBalance(),
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
				$this->setId($value);
				break;
			case 1:
				$this->setCreditorFirstname($value);
				break;
			case 2:
				$this->setCreditorId($value);
				break;
			case 3:
				$this->setContractId($value);
				break;
			case 4:
				$this->setContractName($value);
				break;
			case 5:
				$this->setRegulationYear($value);
				break;
			case 6:
				$this->setStartBalance($value);
				break;
			case 7:
				$this->setContractActivatedAt($value);
				break;
			case 8:
				$this->setContractBalance($value);
				break;
			case 9:
				$this->setRequlation($value);
				break;
			case 10:
				$this->setPaid($value);
				break;
			case 11:
				$this->setPaidForCurrentYear($value);
				break;
			case 12:
				$this->setCapitalized($value);
				break;
			case 13:
				$this->setUnpaid($value);
				break;
			case 14:
				$this->setUnpaidInPast($value);
				break;
			case 15:
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

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setCreditorFirstname($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setCreditorId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setContractId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setContractName($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setRegulationYear($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setStartBalance($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setContractActivatedAt($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setContractBalance($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setRequlation($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setPaid($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setPaidForCurrentYear($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setCapitalized($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setUnpaid($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setUnpaidInPast($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setEndBalance($arr[$keys[15]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(RegulationPeer::DATABASE_NAME);

		if ($this->isColumnModified(RegulationPeer::ID)) $criteria->add(RegulationPeer::ID, $this->id);
		if ($this->isColumnModified(RegulationPeer::CREDITOR_FULLNAME)) $criteria->add(RegulationPeer::CREDITOR_FULLNAME, $this->creditor_fullname);
		if ($this->isColumnModified(RegulationPeer::CREDITOR_ID)) $criteria->add(RegulationPeer::CREDITOR_ID, $this->creditor_id);
		if ($this->isColumnModified(RegulationPeer::CONTRACT_ID)) $criteria->add(RegulationPeer::CONTRACT_ID, $this->contract_id);
		if ($this->isColumnModified(RegulationPeer::CONTRACT_NAME)) $criteria->add(RegulationPeer::CONTRACT_NAME, $this->contract_name);
		if ($this->isColumnModified(RegulationPeer::REGULATION_YEAR)) $criteria->add(RegulationPeer::REGULATION_YEAR, $this->regulation_year);
		if ($this->isColumnModified(RegulationPeer::START_BALANCE)) $criteria->add(RegulationPeer::START_BALANCE, $this->start_balance);
		if ($this->isColumnModified(RegulationPeer::CONTRACT_ACTIVATED_AT)) $criteria->add(RegulationPeer::CONTRACT_ACTIVATED_AT, $this->contract_activated_at);
		if ($this->isColumnModified(RegulationPeer::CONTRACT_BALANCE)) $criteria->add(RegulationPeer::CONTRACT_BALANCE, $this->contract_balance);
		if ($this->isColumnModified(RegulationPeer::REGULATION)) $criteria->add(RegulationPeer::REGULATION, $this->regulation);
		if ($this->isColumnModified(RegulationPeer::PAID)) $criteria->add(RegulationPeer::PAID, $this->paid);
		if ($this->isColumnModified(RegulationPeer::PAID_FOR_CURRENT_YEAR)) $criteria->add(RegulationPeer::PAID_FOR_CURRENT_YEAR, $this->paid_for_current_year);
		if ($this->isColumnModified(RegulationPeer::CAPITALIZED)) $criteria->add(RegulationPeer::CAPITALIZED, $this->capitalized);
		if ($this->isColumnModified(RegulationPeer::UNPAID)) $criteria->add(RegulationPeer::UNPAID, $this->unpaid);
		if ($this->isColumnModified(RegulationPeer::UNPAID_IN_PAST)) $criteria->add(RegulationPeer::UNPAID_IN_PAST, $this->unpaid_in_past);
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

		$criteria->add(RegulationPeer::ID, $this->id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     string
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (id column).
	 *
	 * @param      string $key Primary key.
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
	 * @param      object $copyObj An object of Regulation (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setId($this->id);

		$copyObj->setCreditorFirstname($this->creditor_fullname);

		$copyObj->setCreditorId($this->creditor_id);

		$copyObj->setContractId($this->contract_id);

		$copyObj->setContractName($this->contract_name);

		$copyObj->setRegulationYear($this->regulation_year);

		$copyObj->setStartBalance($this->start_balance);

		$copyObj->setContractActivatedAt($this->contract_activated_at);

		$copyObj->setContractBalance($this->contract_balance);

		$copyObj->setRequlation($this->regulation);

		$copyObj->setPaid($this->paid);

		$copyObj->setPaidForCurrentYear($this->paid_for_current_year);

		$copyObj->setCapitalized($this->capitalized);

		$copyObj->setUnpaid($this->unpaid);

		$copyObj->setUnpaidInPast($this->unpaid_in_past);

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
	 * Declares an association between this object and a Contract object.
	 *
	 * @param      Contract $v
	 * @return     Regulation The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setContract(Contract $v = null)
	{
		if ($v === null) {
			$this->setContractId(NULL);
		} else {
			$this->setContractId($v->getId());
		}

		$this->aContract = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Contract object, it will not be re-added.
		if ($v !== null) {
			$v->addRegulation($this);
		}

		return $this;
	}


	/**
	 * Get the associated Contract object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Contract The associated Contract object.
	 * @throws     PropelException
	 */
	public function getContract(PropelPDO $con = null)
	{
		if ($this->aContract === null && ($this->contract_id !== null)) {
			$this->aContract = ContractPeer::retrieveByPk($this->contract_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aContract->addRegulations($this);
			 */
		}
		return $this->aContract;
	}

	/**
	 * Declares an association between this object and a Creditor object.
	 *
	 * @param      Creditor $v
	 * @return     Regulation The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setCreditor(Creditor $v = null)
	{
		if ($v === null) {
			$this->setCreditorId(NULL);
		} else {
			$this->setCreditorId($v->getId());
		}

		$this->aCreditor = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Creditor object, it will not be re-added.
		if ($v !== null) {
			$v->addRegulation($this);
		}

		return $this;
	}


	/**
	 * Get the associated Creditor object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Creditor The associated Creditor object.
	 * @throws     PropelException
	 */
	public function getCreditor(PropelPDO $con = null)
	{
		if ($this->aCreditor === null && ($this->creditor_id !== null)) {
			$this->aCreditor = CreditorPeer::retrieveByPk($this->creditor_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aCreditor->addRegulations($this);
			 */
		}
		return $this->aCreditor;
	}

	/**
	 * Declares an association between this object and a RegulationYear object.
	 *
	 * @param      RegulationYear $v
	 * @return     Regulation The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setRegulationYearRelatedByRegulationYear(RegulationYear $v = null)
	{
		if ($v === null) {
			$this->setRegulationYear(NULL);
		} else {
			$this->setRegulationYear($v->getId());
		}

		$this->aRegulationYearRelatedByRegulationYear = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the RegulationYear object, it will not be re-added.
		if ($v !== null) {
			$v->addRegulation($this);
		}

		return $this;
	}


	/**
	 * Get the associated RegulationYear object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     RegulationYear The associated RegulationYear object.
	 * @throws     PropelException
	 */
	public function getRegulationYearRelatedByRegulationYear(PropelPDO $con = null)
	{
		if ($this->aRegulationYearRelatedByRegulationYear === null && (($this->regulation_year !== "" && $this->regulation_year !== null))) {
			$this->aRegulationYearRelatedByRegulationYear = RegulationYearPeer::retrieveByPk($this->regulation_year);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aRegulationYearRelatedByRegulationYear->addRegulations($this);
			 */
		}
		return $this->aRegulationYearRelatedByRegulationYear;
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

			$this->aContract = null;
			$this->aCreditor = null;
			$this->aRegulationYearRelatedByRegulationYear = null;
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
