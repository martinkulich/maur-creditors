<?php

/**
 * Base class that represents a row from the 'settlement' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseSettlement extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        SettlementPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the contract_id field.
	 * @var        int
	 */
	protected $contract_id;

	/**
	 * The value for the date field.
	 * @var        string
	 */
	protected $date;

	/**
	 * The value for the interest field.
	 * @var        string
	 */
	protected $interest;

	/**
	 * The value for the capitalized field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $capitalized;

	/**
	 * The value for the balance field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $balance;

	/**
	 * The value for the note field.
	 * @var        string
	 */
	protected $note;

	/**
	 * The value for the bank_account field.
	 * @var        string
	 */
	protected $bank_account;

	/**
	 * The value for the cash field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $cash;

	/**
	 * The value for the settlement_type field.
	 * Note: this column has a database default value of: 'in_period'
	 * @var        string
	 */
	protected $settlement_type;

	/**
	 * The value for the manual_interest field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $manual_interest;

	/**
	 * The value for the manual_balance field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $manual_balance;

	/**
	 * The value for the currency_rate field.
	 * Note: this column has a database default value of: '1'
	 * @var        string
	 */
	protected $currency_rate;

	/**
	 * @var        Contract
	 */
	protected $aContract;

	/**
	 * @var        array Allocation[] Collection to store aggregation of Allocation objects.
	 */
	protected $collAllocations;

	/**
	 * @var        Criteria The criteria used to select the current contents of collAllocations.
	 */
	private $lastAllocationCriteria = null;

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
	
	const PEER = 'SettlementPeer';

	/**
	 * Applies default values to this object.
	 * This method should be called from the object's constructor (or
	 * equivalent initialization method).
	 * @see        __construct()
	 */
	public function applyDefaultValues()
	{
		$this->capitalized = '0';
		$this->balance = '0';
		$this->cash = false;
		$this->settlement_type = 'in_period';
		$this->manual_interest = false;
		$this->manual_balance = false;
		$this->currency_rate = '1';
	}

	/**
	 * Initializes internal state of BaseSettlement object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

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
	 * Get the [contract_id] column value.
	 * 
	 * @return     int
	 */
	public function getContractId()
	{
		return $this->contract_id;
	}

	/**
	 * Get the [optionally formatted] temporal [date] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getDate($format = 'Y-m-d')
	{
		if ($this->date === null) {
			return null;
		}



		try {
			$dt = new DateTime($this->date);
		} catch (Exception $x) {
			throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->date, true), $x);
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
	 * Get the [interest] column value.
	 * 
	 * @return     string
	 */
	public function getInterest()
	{
		return $this->interest;
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
	 * Get the [balance] column value.
	 * 
	 * @return     string
	 */
	public function getBalance()
	{
		return $this->balance;
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
	 * Get the [bank_account] column value.
	 * 
	 * @return     string
	 */
	public function getBankAccount()
	{
		return $this->bank_account;
	}

	/**
	 * Get the [cash] column value.
	 * 
	 * @return     boolean
	 */
	public function getCash()
	{
		return $this->cash;
	}

	/**
	 * Get the [settlement_type] column value.
	 * 
	 * @return     string
	 */
	public function getSettlementType()
	{
		return $this->settlement_type;
	}

	/**
	 * Get the [manual_interest] column value.
	 * 
	 * @return     boolean
	 */
	public function getManualInterest()
	{
		return $this->manual_interest;
	}

	/**
	 * Get the [manual_balance] column value.
	 * 
	 * @return     boolean
	 */
	public function getManualBalance()
	{
		return $this->manual_balance;
	}

	/**
	 * Get the [currency_rate] column value.
	 * 
	 * @return     string
	 */
	public function getCurrencyRate()
	{
		return $this->currency_rate;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     Settlement The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = SettlementPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [contract_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Settlement The current object (for fluent API support)
	 */
	public function setContractId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->contract_id !== $v) {
			$this->contract_id = $v;
			$this->modifiedColumns[] = SettlementPeer::CONTRACT_ID;
		}

		if ($this->aContract !== null && $this->aContract->getId() !== $v) {
			$this->aContract = null;
		}

		return $this;
	} // setContractId()

	/**
	 * Sets the value of [date] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Settlement The current object (for fluent API support)
	 */
	public function setDate($v)
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

		if ( $this->date !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->date !== null && $tmpDt = new DateTime($this->date)) ? $tmpDt->format('Y-m-d') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->date = ($dt ? $dt->format('Y-m-d') : null);
				$this->modifiedColumns[] = SettlementPeer::DATE;
			}
		} // if either are not null

		return $this;
	} // setDate()

	/**
	 * Set the value of [interest] column.
	 * 
	 * @param      string $v new value
	 * @return     Settlement The current object (for fluent API support)
	 */
	public function setInterest($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->interest !== $v) {
			$this->interest = $v;
			$this->modifiedColumns[] = SettlementPeer::INTEREST;
		}

		return $this;
	} // setInterest()

	/**
	 * Set the value of [capitalized] column.
	 * 
	 * @param      string $v new value
	 * @return     Settlement The current object (for fluent API support)
	 */
	public function setCapitalized($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->capitalized !== $v || $this->isNew()) {
			$this->capitalized = $v;
			$this->modifiedColumns[] = SettlementPeer::CAPITALIZED;
		}

		return $this;
	} // setCapitalized()

	/**
	 * Set the value of [balance] column.
	 * 
	 * @param      string $v new value
	 * @return     Settlement The current object (for fluent API support)
	 */
	public function setBalance($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->balance !== $v || $this->isNew()) {
			$this->balance = $v;
			$this->modifiedColumns[] = SettlementPeer::BALANCE;
		}

		return $this;
	} // setBalance()

	/**
	 * Set the value of [note] column.
	 * 
	 * @param      string $v new value
	 * @return     Settlement The current object (for fluent API support)
	 */
	public function setNote($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->note !== $v) {
			$this->note = $v;
			$this->modifiedColumns[] = SettlementPeer::NOTE;
		}

		return $this;
	} // setNote()

	/**
	 * Set the value of [bank_account] column.
	 * 
	 * @param      string $v new value
	 * @return     Settlement The current object (for fluent API support)
	 */
	public function setBankAccount($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->bank_account !== $v) {
			$this->bank_account = $v;
			$this->modifiedColumns[] = SettlementPeer::BANK_ACCOUNT;
		}

		return $this;
	} // setBankAccount()

	/**
	 * Set the value of [cash] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Settlement The current object (for fluent API support)
	 */
	public function setCash($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->cash !== $v || $this->isNew()) {
			$this->cash = $v;
			$this->modifiedColumns[] = SettlementPeer::CASH;
		}

		return $this;
	} // setCash()

	/**
	 * Set the value of [settlement_type] column.
	 * 
	 * @param      string $v new value
	 * @return     Settlement The current object (for fluent API support)
	 */
	public function setSettlementType($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->settlement_type !== $v || $this->isNew()) {
			$this->settlement_type = $v;
			$this->modifiedColumns[] = SettlementPeer::SETTLEMENT_TYPE;
		}

		return $this;
	} // setSettlementType()

	/**
	 * Set the value of [manual_interest] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Settlement The current object (for fluent API support)
	 */
	public function setManualInterest($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->manual_interest !== $v || $this->isNew()) {
			$this->manual_interest = $v;
			$this->modifiedColumns[] = SettlementPeer::MANUAL_INTEREST;
		}

		return $this;
	} // setManualInterest()

	/**
	 * Set the value of [manual_balance] column.
	 * 
	 * @param      boolean $v new value
	 * @return     Settlement The current object (for fluent API support)
	 */
	public function setManualBalance($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->manual_balance !== $v || $this->isNew()) {
			$this->manual_balance = $v;
			$this->modifiedColumns[] = SettlementPeer::MANUAL_BALANCE;
		}

		return $this;
	} // setManualBalance()

	/**
	 * Set the value of [currency_rate] column.
	 * 
	 * @param      string $v new value
	 * @return     Settlement The current object (for fluent API support)
	 */
	public function setCurrencyRate($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->currency_rate !== $v || $this->isNew()) {
			$this->currency_rate = $v;
			$this->modifiedColumns[] = SettlementPeer::CURRENCY_RATE;
		}

		return $this;
	} // setCurrencyRate()

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
			if ($this->capitalized !== '0') {
				return false;
			}

			if ($this->balance !== '0') {
				return false;
			}

			if ($this->cash !== false) {
				return false;
			}

			if ($this->settlement_type !== 'in_period') {
				return false;
			}

			if ($this->manual_interest !== false) {
				return false;
			}

			if ($this->manual_balance !== false) {
				return false;
			}

			if ($this->currency_rate !== '1') {
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

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->contract_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->date = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->interest = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->capitalized = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->balance = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->note = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->bank_account = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->cash = ($row[$startcol + 8] !== null) ? (boolean) $row[$startcol + 8] : null;
			$this->settlement_type = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->manual_interest = ($row[$startcol + 10] !== null) ? (boolean) $row[$startcol + 10] : null;
			$this->manual_balance = ($row[$startcol + 11] !== null) ? (boolean) $row[$startcol + 11] : null;
			$this->currency_rate = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 13; // 13 = SettlementPeer::NUM_COLUMNS - SettlementPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Settlement object", $e);
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

		if ($this->aContract !== null && $this->contract_id !== $this->aContract->getId()) {
			$this->aContract = null;
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
			$con = Propel::getConnection(SettlementPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = SettlementPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aContract = null;
			$this->collAllocations = null;
			$this->lastAllocationCriteria = null;

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
			$con = Propel::getConnection(SettlementPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseSettlement:delete:pre') as $callable)
			{
			  if (call_user_func($callable, $this, $con))
			  {
			    $con->commit();
			
			    return;
			  }
			}

			if ($ret) {
				SettlementPeer::doDelete($this, $con);
				$this->postDelete($con);
				// symfony_behaviors behavior
				foreach (sfMixer::getCallables('BaseSettlement:delete:post') as $callable)
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
			$con = Propel::getConnection(SettlementPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseSettlement:save:pre') as $callable)
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
				foreach (sfMixer::getCallables('BaseSettlement:save:post') as $callable)
				{
				  call_user_func($callable, $this, $con, $affectedRows);
				}

				SettlementPeer::addInstanceToPool($this);
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

			if ($this->isNew() ) {
				$this->modifiedColumns[] = SettlementPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = SettlementPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += SettlementPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collAllocations !== null) {
				foreach ($this->collAllocations as $referrerFK) {
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


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aContract !== null) {
				if (!$this->aContract->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aContract->getValidationFailures());
				}
			}


			if (($retval = SettlementPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collAllocations !== null) {
					foreach ($this->collAllocations as $referrerFK) {
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
		$pos = SettlementPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getContractId();
				break;
			case 2:
				return $this->getDate();
				break;
			case 3:
				return $this->getInterest();
				break;
			case 4:
				return $this->getCapitalized();
				break;
			case 5:
				return $this->getBalance();
				break;
			case 6:
				return $this->getNote();
				break;
			case 7:
				return $this->getBankAccount();
				break;
			case 8:
				return $this->getCash();
				break;
			case 9:
				return $this->getSettlementType();
				break;
			case 10:
				return $this->getManualInterest();
				break;
			case 11:
				return $this->getManualBalance();
				break;
			case 12:
				return $this->getCurrencyRate();
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
		$keys = SettlementPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getContractId(),
			$keys[2] => $this->getDate(),
			$keys[3] => $this->getInterest(),
			$keys[4] => $this->getCapitalized(),
			$keys[5] => $this->getBalance(),
			$keys[6] => $this->getNote(),
			$keys[7] => $this->getBankAccount(),
			$keys[8] => $this->getCash(),
			$keys[9] => $this->getSettlementType(),
			$keys[10] => $this->getManualInterest(),
			$keys[11] => $this->getManualBalance(),
			$keys[12] => $this->getCurrencyRate(),
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
		$pos = SettlementPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setContractId($value);
				break;
			case 2:
				$this->setDate($value);
				break;
			case 3:
				$this->setInterest($value);
				break;
			case 4:
				$this->setCapitalized($value);
				break;
			case 5:
				$this->setBalance($value);
				break;
			case 6:
				$this->setNote($value);
				break;
			case 7:
				$this->setBankAccount($value);
				break;
			case 8:
				$this->setCash($value);
				break;
			case 9:
				$this->setSettlementType($value);
				break;
			case 10:
				$this->setManualInterest($value);
				break;
			case 11:
				$this->setManualBalance($value);
				break;
			case 12:
				$this->setCurrencyRate($value);
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
		$keys = SettlementPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setContractId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setDate($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setInterest($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setCapitalized($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setBalance($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setNote($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setBankAccount($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setCash($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setSettlementType($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setManualInterest($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setManualBalance($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setCurrencyRate($arr[$keys[12]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(SettlementPeer::DATABASE_NAME);

		if ($this->isColumnModified(SettlementPeer::ID)) $criteria->add(SettlementPeer::ID, $this->id);
		if ($this->isColumnModified(SettlementPeer::CONTRACT_ID)) $criteria->add(SettlementPeer::CONTRACT_ID, $this->contract_id);
		if ($this->isColumnModified(SettlementPeer::DATE)) $criteria->add(SettlementPeer::DATE, $this->date);
		if ($this->isColumnModified(SettlementPeer::INTEREST)) $criteria->add(SettlementPeer::INTEREST, $this->interest);
		if ($this->isColumnModified(SettlementPeer::CAPITALIZED)) $criteria->add(SettlementPeer::CAPITALIZED, $this->capitalized);
		if ($this->isColumnModified(SettlementPeer::BALANCE)) $criteria->add(SettlementPeer::BALANCE, $this->balance);
		if ($this->isColumnModified(SettlementPeer::NOTE)) $criteria->add(SettlementPeer::NOTE, $this->note);
		if ($this->isColumnModified(SettlementPeer::BANK_ACCOUNT)) $criteria->add(SettlementPeer::BANK_ACCOUNT, $this->bank_account);
		if ($this->isColumnModified(SettlementPeer::CASH)) $criteria->add(SettlementPeer::CASH, $this->cash);
		if ($this->isColumnModified(SettlementPeer::SETTLEMENT_TYPE)) $criteria->add(SettlementPeer::SETTLEMENT_TYPE, $this->settlement_type);
		if ($this->isColumnModified(SettlementPeer::MANUAL_INTEREST)) $criteria->add(SettlementPeer::MANUAL_INTEREST, $this->manual_interest);
		if ($this->isColumnModified(SettlementPeer::MANUAL_BALANCE)) $criteria->add(SettlementPeer::MANUAL_BALANCE, $this->manual_balance);
		if ($this->isColumnModified(SettlementPeer::CURRENCY_RATE)) $criteria->add(SettlementPeer::CURRENCY_RATE, $this->currency_rate);

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
		$criteria = new Criteria(SettlementPeer::DATABASE_NAME);

		$criteria->add(SettlementPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Settlement (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setContractId($this->contract_id);

		$copyObj->setDate($this->date);

		$copyObj->setInterest($this->interest);

		$copyObj->setCapitalized($this->capitalized);

		$copyObj->setBalance($this->balance);

		$copyObj->setNote($this->note);

		$copyObj->setBankAccount($this->bank_account);

		$copyObj->setCash($this->cash);

		$copyObj->setSettlementType($this->settlement_type);

		$copyObj->setManualInterest($this->manual_interest);

		$copyObj->setManualBalance($this->manual_balance);

		$copyObj->setCurrencyRate($this->currency_rate);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getAllocations() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addAllocation($relObj->copy($deepCopy));
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
	 * @return     Settlement Clone of current object.
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
	 * @return     SettlementPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new SettlementPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Contract object.
	 *
	 * @param      Contract $v
	 * @return     Settlement The current object (for fluent API support)
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
			$v->addSettlement($this);
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
			   $this->aContract->addSettlements($this);
			 */
		}
		return $this->aContract;
	}

	/**
	 * Clears out the collAllocations collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addAllocations()
	 */
	public function clearAllocations()
	{
		$this->collAllocations = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collAllocations collection (array).
	 *
	 * By default this just sets the collAllocations collection to an empty array (like clearcollAllocations());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initAllocations()
	{
		$this->collAllocations = array();
	}

	/**
	 * Gets an array of Allocation objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Settlement has previously been saved, it will retrieve
	 * related Allocations from storage. If this Settlement is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Allocation[]
	 * @throws     PropelException
	 */
	public function getAllocations($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SettlementPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collAllocations === null) {
			if ($this->isNew()) {
			   $this->collAllocations = array();
			} else {

				$criteria->add(AllocationPeer::SETTLEMENT_ID, $this->id);

				AllocationPeer::addSelectColumns($criteria);
				$this->collAllocations = AllocationPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(AllocationPeer::SETTLEMENT_ID, $this->id);

				AllocationPeer::addSelectColumns($criteria);
				if (!isset($this->lastAllocationCriteria) || !$this->lastAllocationCriteria->equals($criteria)) {
					$this->collAllocations = AllocationPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastAllocationCriteria = $criteria;
		return $this->collAllocations;
	}

	/**
	 * Returns the number of related Allocation objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Allocation objects.
	 * @throws     PropelException
	 */
	public function countAllocations(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SettlementPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collAllocations === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(AllocationPeer::SETTLEMENT_ID, $this->id);

				$count = AllocationPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(AllocationPeer::SETTLEMENT_ID, $this->id);

				if (!isset($this->lastAllocationCriteria) || !$this->lastAllocationCriteria->equals($criteria)) {
					$count = AllocationPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collAllocations);
				}
			} else {
				$count = count($this->collAllocations);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Allocation object to this object
	 * through the Allocation foreign key attribute.
	 *
	 * @param      Allocation $l Allocation
	 * @return     void
	 * @throws     PropelException
	 */
	public function addAllocation(Allocation $l)
	{
		if ($this->collAllocations === null) {
			$this->initAllocations();
		}
		if (!in_array($l, $this->collAllocations, true)) { // only add it if the **same** object is not already associated
			array_push($this->collAllocations, $l);
			$l->setSettlement($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Settlement is new, it will return
	 * an empty collection; or if this Settlement has previously
	 * been saved, it will retrieve related Allocations from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Settlement.
	 */
	public function getAllocationsJoinOutgoingPayment($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SettlementPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collAllocations === null) {
			if ($this->isNew()) {
				$this->collAllocations = array();
			} else {

				$criteria->add(AllocationPeer::SETTLEMENT_ID, $this->id);

				$this->collAllocations = AllocationPeer::doSelectJoinOutgoingPayment($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(AllocationPeer::SETTLEMENT_ID, $this->id);

			if (!isset($this->lastAllocationCriteria) || !$this->lastAllocationCriteria->equals($criteria)) {
				$this->collAllocations = AllocationPeer::doSelectJoinOutgoingPayment($criteria, $con, $join_behavior);
			}
		}
		$this->lastAllocationCriteria = $criteria;

		return $this->collAllocations;
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
			if ($this->collAllocations) {
				foreach ((array) $this->collAllocations as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collAllocations = null;
			$this->aContract = null;
	}

	// symfony_behaviors behavior
	
	/**
	 * Calls methods defined via {@link sfMixer}.
	 */
	public function __call($method, $arguments)
	{
	  if (!$callable = sfMixer::getCallable('BaseSettlement:'.$method))
	  {
	    throw new sfException(sprintf('Call to undefined method BaseSettlement::%s', $method));
	  }
	
	  array_unshift($arguments, $this);
	
	  return call_user_func_array($callable, $arguments);
	}

} // BaseSettlement
