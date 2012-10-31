<?php

/**
 * Base class that represents a row from the 'contract' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseContract extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        ContractPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the creditor_id field.
	 * @var        int
	 */
	protected $creditor_id;

	/**
	 * The value for the created_at field.
	 * @var        string
	 */
	protected $created_at;

	/**
	 * The value for the activated_at field.
	 * @var        string
	 */
	protected $activated_at;

	/**
	 * The value for the period field.
	 * @var        int
	 */
	protected $period;

	/**
	 * The value for the interest_rate field.
	 * @var        string
	 */
	protected $interest_rate;

	/**
	 * The value for the amount field.
	 * @var        string
	 */
	protected $amount;

	/**
	 * The value for the name field.
	 * @var        string
	 */
	protected $name;

	/**
	 * The value for the closed_at field.
	 * @var        string
	 */
	protected $closed_at;

	/**
	 * The value for the note field.
	 * @var        string
	 */
	protected $note;

	/**
	 * The value for the currency_code field.
	 * @var        string
	 */
	protected $currency_code;

	/**
	 * The value for the first_settlement_date field.
	 * @var        string
	 */
	protected $first_settlement_date;

	/**
	 * @var        Creditor
	 */
	protected $aCreditor;

	/**
	 * @var        Currency
	 */
	protected $aCurrency;

	/**
	 * @var        array Payment[] Collection to store aggregation of Payment objects.
	 */
	protected $collPayments;

	/**
	 * @var        Criteria The criteria used to select the current contents of collPayments.
	 */
	private $lastPaymentCriteria = null;

	/**
	 * @var        array Settlement[] Collection to store aggregation of Settlement objects.
	 */
	protected $collSettlements;

	/**
	 * @var        Criteria The criteria used to select the current contents of collSettlements.
	 */
	private $lastSettlementCriteria = null;

	/**
	 * @var        array Regulation[] Collection to store aggregation of Regulation objects.
	 */
	protected $collRegulations;

	/**
	 * @var        Criteria The criteria used to select the current contents of collRegulations.
	 */
	private $lastRegulationCriteria = null;

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
	
	const PEER = 'ContractPeer';

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
	 * Get the [creditor_id] column value.
	 * 
	 * @return     int
	 */
	public function getCreditorId()
	{
		return $this->creditor_id;
	}

	/**
	 * Get the [optionally formatted] temporal [created_at] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getCreatedAt($format = 'Y-m-d')
	{
		if ($this->created_at === null) {
			return null;
		}



		try {
			$dt = new DateTime($this->created_at);
		} catch (Exception $x) {
			throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
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
	 * Get the [optionally formatted] temporal [activated_at] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getActivatedAt($format = 'Y-m-d')
	{
		if ($this->activated_at === null) {
			return null;
		}



		try {
			$dt = new DateTime($this->activated_at);
		} catch (Exception $x) {
			throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->activated_at, true), $x);
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
	 * Get the [period] column value.
	 * 
	 * @return     int
	 */
	public function getPeriod()
	{
		return $this->period;
	}

	/**
	 * Get the [interest_rate] column value.
	 * 
	 * @return     string
	 */
	public function getInterestRate()
	{
		return $this->interest_rate;
	}

	/**
	 * Get the [amount] column value.
	 * 
	 * @return     string
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * Get the [name] column value.
	 * 
	 * @return     string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get the [optionally formatted] temporal [closed_at] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getClosedAt($format = 'Y-m-d')
	{
		if ($this->closed_at === null) {
			return null;
		}



		try {
			$dt = new DateTime($this->closed_at);
		} catch (Exception $x) {
			throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->closed_at, true), $x);
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
	 * Get the [note] column value.
	 * 
	 * @return     string
	 */
	public function getNote()
	{
		return $this->note;
	}

	/**
	 * Get the [currency_code] column value.
	 * 
	 * @return     string
	 */
	public function getCurrencyCode()
	{
		return $this->currency_code;
	}

	/**
	 * Get the [optionally formatted] temporal [first_settlement_date] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getFirstSettlementDate($format = 'Y-m-d')
	{
		if ($this->first_settlement_date === null) {
			return null;
		}



		try {
			$dt = new DateTime($this->first_settlement_date);
		} catch (Exception $x) {
			throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->first_settlement_date, true), $x);
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
	 * @return     Contract The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = ContractPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [creditor_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Contract The current object (for fluent API support)
	 */
	public function setCreditorId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->creditor_id !== $v) {
			$this->creditor_id = $v;
			$this->modifiedColumns[] = ContractPeer::CREDITOR_ID;
		}

		if ($this->aCreditor !== null && $this->aCreditor->getId() !== $v) {
			$this->aCreditor = null;
		}

		return $this;
	} // setCreditorId()

	/**
	 * Sets the value of [created_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Contract The current object (for fluent API support)
	 */
	public function setCreatedAt($v)
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

		if ( $this->created_at !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->created_at = ($dt ? $dt->format('Y-m-d') : null);
				$this->modifiedColumns[] = ContractPeer::CREATED_AT;
			}
		} // if either are not null

		return $this;
	} // setCreatedAt()

	/**
	 * Sets the value of [activated_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Contract The current object (for fluent API support)
	 */
	public function setActivatedAt($v)
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

		if ( $this->activated_at !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->activated_at !== null && $tmpDt = new DateTime($this->activated_at)) ? $tmpDt->format('Y-m-d') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->activated_at = ($dt ? $dt->format('Y-m-d') : null);
				$this->modifiedColumns[] = ContractPeer::ACTIVATED_AT;
			}
		} // if either are not null

		return $this;
	} // setActivatedAt()

	/**
	 * Set the value of [period] column.
	 * 
	 * @param      int $v new value
	 * @return     Contract The current object (for fluent API support)
	 */
	public function setPeriod($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->period !== $v) {
			$this->period = $v;
			$this->modifiedColumns[] = ContractPeer::PERIOD;
		}

		return $this;
	} // setPeriod()

	/**
	 * Set the value of [interest_rate] column.
	 * 
	 * @param      string $v new value
	 * @return     Contract The current object (for fluent API support)
	 */
	public function setInterestRate($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->interest_rate !== $v) {
			$this->interest_rate = $v;
			$this->modifiedColumns[] = ContractPeer::INTEREST_RATE;
		}

		return $this;
	} // setInterestRate()

	/**
	 * Set the value of [amount] column.
	 * 
	 * @param      string $v new value
	 * @return     Contract The current object (for fluent API support)
	 */
	public function setAmount($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->amount !== $v) {
			$this->amount = $v;
			$this->modifiedColumns[] = ContractPeer::AMOUNT;
		}

		return $this;
	} // setAmount()

	/**
	 * Set the value of [name] column.
	 * 
	 * @param      string $v new value
	 * @return     Contract The current object (for fluent API support)
	 */
	public function setName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->name !== $v) {
			$this->name = $v;
			$this->modifiedColumns[] = ContractPeer::NAME;
		}

		return $this;
	} // setName()

	/**
	 * Sets the value of [closed_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Contract The current object (for fluent API support)
	 */
	public function setClosedAt($v)
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

		if ( $this->closed_at !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->closed_at !== null && $tmpDt = new DateTime($this->closed_at)) ? $tmpDt->format('Y-m-d') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->closed_at = ($dt ? $dt->format('Y-m-d') : null);
				$this->modifiedColumns[] = ContractPeer::CLOSED_AT;
			}
		} // if either are not null

		return $this;
	} // setClosedAt()

	/**
	 * Set the value of [note] column.
	 * 
	 * @param      string $v new value
	 * @return     Contract The current object (for fluent API support)
	 */
	public function setNote($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->note !== $v) {
			$this->note = $v;
			$this->modifiedColumns[] = ContractPeer::NOTE;
		}

		return $this;
	} // setNote()

	/**
	 * Set the value of [currency_code] column.
	 * 
	 * @param      string $v new value
	 * @return     Contract The current object (for fluent API support)
	 */
	public function setCurrencyCode($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->currency_code !== $v) {
			$this->currency_code = $v;
			$this->modifiedColumns[] = ContractPeer::CURRENCY_CODE;
		}

		if ($this->aCurrency !== null && $this->aCurrency->getCode() !== $v) {
			$this->aCurrency = null;
		}

		return $this;
	} // setCurrencyCode()

	/**
	 * Sets the value of [first_settlement_date] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Contract The current object (for fluent API support)
	 */
	public function setFirstSettlementDate($v)
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

		if ( $this->first_settlement_date !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->first_settlement_date !== null && $tmpDt = new DateTime($this->first_settlement_date)) ? $tmpDt->format('Y-m-d') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->first_settlement_date = ($dt ? $dt->format('Y-m-d') : null);
				$this->modifiedColumns[] = ContractPeer::FIRST_SETTLEMENT_DATE;
			}
		} // if either are not null

		return $this;
	} // setFirstSettlementDate()

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
			$this->creditor_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->created_at = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->activated_at = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->period = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->interest_rate = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->amount = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->name = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->closed_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->note = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->currency_code = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->first_settlement_date = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 12; // 12 = ContractPeer::NUM_COLUMNS - ContractPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Contract object", $e);
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
		if ($this->aCurrency !== null && $this->currency_code !== $this->aCurrency->getCode()) {
			$this->aCurrency = null;
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
			$con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = ContractPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aCreditor = null;
			$this->aCurrency = null;
			$this->collPayments = null;
			$this->lastPaymentCriteria = null;

			$this->collSettlements = null;
			$this->lastSettlementCriteria = null;

			$this->collRegulations = null;
			$this->lastRegulationCriteria = null;

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
			$con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseContract:delete:pre') as $callable)
			{
			  if (call_user_func($callable, $this, $con))
			  {
			    $con->commit();
			
			    return;
			  }
			}

			if ($ret) {
				ContractPeer::doDelete($this, $con);
				$this->postDelete($con);
				// symfony_behaviors behavior
				foreach (sfMixer::getCallables('BaseContract:delete:post') as $callable)
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
			$con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseContract:save:pre') as $callable)
			{
			  if (is_integer($affectedRows = call_user_func($callable, $this, $con)))
			  {
			    $con->commit();
			
			    return $affectedRows;
			  }
			}

			// symfony_timestampable behavior
			
			if ($isInsert) {
				$ret = $ret && $this->preInsert($con);
				// symfony_timestampable behavior
				if (!$this->isColumnModified(ContractPeer::CREATED_AT))
				{
				  $this->setCreatedAt(time());
				}

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
				foreach (sfMixer::getCallables('BaseContract:save:post') as $callable)
				{
				  call_user_func($callable, $this, $con, $affectedRows);
				}

				ContractPeer::addInstanceToPool($this);
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

			if ($this->aCreditor !== null) {
				if ($this->aCreditor->isModified() || $this->aCreditor->isNew()) {
					$affectedRows += $this->aCreditor->save($con);
				}
				$this->setCreditor($this->aCreditor);
			}

			if ($this->aCurrency !== null) {
				if ($this->aCurrency->isModified() || $this->aCurrency->isNew()) {
					$affectedRows += $this->aCurrency->save($con);
				}
				$this->setCurrency($this->aCurrency);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = ContractPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = ContractPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += ContractPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collPayments !== null) {
				foreach ($this->collPayments as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collSettlements !== null) {
				foreach ($this->collSettlements as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collRegulations !== null) {
				foreach ($this->collRegulations as $referrerFK) {
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

			if ($this->aCreditor !== null) {
				if (!$this->aCreditor->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aCreditor->getValidationFailures());
				}
			}

			if ($this->aCurrency !== null) {
				if (!$this->aCurrency->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aCurrency->getValidationFailures());
				}
			}


			if (($retval = ContractPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collPayments !== null) {
					foreach ($this->collPayments as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collSettlements !== null) {
					foreach ($this->collSettlements as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collRegulations !== null) {
					foreach ($this->collRegulations as $referrerFK) {
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
		$pos = ContractPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getCreditorId();
				break;
			case 2:
				return $this->getCreatedAt();
				break;
			case 3:
				return $this->getActivatedAt();
				break;
			case 4:
				return $this->getPeriod();
				break;
			case 5:
				return $this->getInterestRate();
				break;
			case 6:
				return $this->getAmount();
				break;
			case 7:
				return $this->getName();
				break;
			case 8:
				return $this->getClosedAt();
				break;
			case 9:
				return $this->getNote();
				break;
			case 10:
				return $this->getCurrencyCode();
				break;
			case 11:
				return $this->getFirstSettlementDate();
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
		$keys = ContractPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getCreditorId(),
			$keys[2] => $this->getCreatedAt(),
			$keys[3] => $this->getActivatedAt(),
			$keys[4] => $this->getPeriod(),
			$keys[5] => $this->getInterestRate(),
			$keys[6] => $this->getAmount(),
			$keys[7] => $this->getName(),
			$keys[8] => $this->getClosedAt(),
			$keys[9] => $this->getNote(),
			$keys[10] => $this->getCurrencyCode(),
			$keys[11] => $this->getFirstSettlementDate(),
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
		$pos = ContractPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setCreditorId($value);
				break;
			case 2:
				$this->setCreatedAt($value);
				break;
			case 3:
				$this->setActivatedAt($value);
				break;
			case 4:
				$this->setPeriod($value);
				break;
			case 5:
				$this->setInterestRate($value);
				break;
			case 6:
				$this->setAmount($value);
				break;
			case 7:
				$this->setName($value);
				break;
			case 8:
				$this->setClosedAt($value);
				break;
			case 9:
				$this->setNote($value);
				break;
			case 10:
				$this->setCurrencyCode($value);
				break;
			case 11:
				$this->setFirstSettlementDate($value);
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
		$keys = ContractPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setCreditorId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setCreatedAt($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setActivatedAt($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setPeriod($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setInterestRate($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setAmount($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setName($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setClosedAt($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setNote($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setCurrencyCode($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setFirstSettlementDate($arr[$keys[11]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(ContractPeer::DATABASE_NAME);

		if ($this->isColumnModified(ContractPeer::ID)) $criteria->add(ContractPeer::ID, $this->id);
		if ($this->isColumnModified(ContractPeer::CREDITOR_ID)) $criteria->add(ContractPeer::CREDITOR_ID, $this->creditor_id);
		if ($this->isColumnModified(ContractPeer::CREATED_AT)) $criteria->add(ContractPeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(ContractPeer::ACTIVATED_AT)) $criteria->add(ContractPeer::ACTIVATED_AT, $this->activated_at);
		if ($this->isColumnModified(ContractPeer::PERIOD)) $criteria->add(ContractPeer::PERIOD, $this->period);
		if ($this->isColumnModified(ContractPeer::INTEREST_RATE)) $criteria->add(ContractPeer::INTEREST_RATE, $this->interest_rate);
		if ($this->isColumnModified(ContractPeer::AMOUNT)) $criteria->add(ContractPeer::AMOUNT, $this->amount);
		if ($this->isColumnModified(ContractPeer::NAME)) $criteria->add(ContractPeer::NAME, $this->name);
		if ($this->isColumnModified(ContractPeer::CLOSED_AT)) $criteria->add(ContractPeer::CLOSED_AT, $this->closed_at);
		if ($this->isColumnModified(ContractPeer::NOTE)) $criteria->add(ContractPeer::NOTE, $this->note);
		if ($this->isColumnModified(ContractPeer::CURRENCY_CODE)) $criteria->add(ContractPeer::CURRENCY_CODE, $this->currency_code);
		if ($this->isColumnModified(ContractPeer::FIRST_SETTLEMENT_DATE)) $criteria->add(ContractPeer::FIRST_SETTLEMENT_DATE, $this->first_settlement_date);

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
		$criteria = new Criteria(ContractPeer::DATABASE_NAME);

		$criteria->add(ContractPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of Contract (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setCreditorId($this->creditor_id);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setActivatedAt($this->activated_at);

		$copyObj->setPeriod($this->period);

		$copyObj->setInterestRate($this->interest_rate);

		$copyObj->setAmount($this->amount);

		$copyObj->setName($this->name);

		$copyObj->setClosedAt($this->closed_at);

		$copyObj->setNote($this->note);

		$copyObj->setCurrencyCode($this->currency_code);

		$copyObj->setFirstSettlementDate($this->first_settlement_date);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getPayments() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addPayment($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getSettlements() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addSettlement($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getRegulations() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addRegulation($relObj->copy($deepCopy));
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
	 * @return     Contract Clone of current object.
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
	 * @return     ContractPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new ContractPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Creditor object.
	 *
	 * @param      Creditor $v
	 * @return     Contract The current object (for fluent API support)
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
			$v->addContract($this);
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
			   $this->aCreditor->addContracts($this);
			 */
		}
		return $this->aCreditor;
	}

	/**
	 * Declares an association between this object and a Currency object.
	 *
	 * @param      Currency $v
	 * @return     Contract The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setCurrency(Currency $v = null)
	{
		if ($v === null) {
			$this->setCurrencyCode(NULL);
		} else {
			$this->setCurrencyCode($v->getCode());
		}

		$this->aCurrency = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Currency object, it will not be re-added.
		if ($v !== null) {
			$v->addContract($this);
		}

		return $this;
	}


	/**
	 * Get the associated Currency object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Currency The associated Currency object.
	 * @throws     PropelException
	 */
	public function getCurrency(PropelPDO $con = null)
	{
		if ($this->aCurrency === null && (($this->currency_code !== "" && $this->currency_code !== null))) {
			$this->aCurrency = CurrencyPeer::retrieveByPk($this->currency_code);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aCurrency->addContracts($this);
			 */
		}
		return $this->aCurrency;
	}

	/**
	 * Clears out the collPayments collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addPayments()
	 */
	public function clearPayments()
	{
		$this->collPayments = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collPayments collection (array).
	 *
	 * By default this just sets the collPayments collection to an empty array (like clearcollPayments());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initPayments()
	{
		$this->collPayments = array();
	}

	/**
	 * Gets an array of Payment objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Contract has previously been saved, it will retrieve
	 * related Payments from storage. If this Contract is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Payment[]
	 * @throws     PropelException
	 */
	public function getPayments($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ContractPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collPayments === null) {
			if ($this->isNew()) {
			   $this->collPayments = array();
			} else {

				$criteria->add(PaymentPeer::CONTRACT_ID, $this->id);

				PaymentPeer::addSelectColumns($criteria);
				$this->collPayments = PaymentPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(PaymentPeer::CONTRACT_ID, $this->id);

				PaymentPeer::addSelectColumns($criteria);
				if (!isset($this->lastPaymentCriteria) || !$this->lastPaymentCriteria->equals($criteria)) {
					$this->collPayments = PaymentPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastPaymentCriteria = $criteria;
		return $this->collPayments;
	}

	/**
	 * Returns the number of related Payment objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Payment objects.
	 * @throws     PropelException
	 */
	public function countPayments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ContractPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collPayments === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(PaymentPeer::CONTRACT_ID, $this->id);

				$count = PaymentPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(PaymentPeer::CONTRACT_ID, $this->id);

				if (!isset($this->lastPaymentCriteria) || !$this->lastPaymentCriteria->equals($criteria)) {
					$count = PaymentPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collPayments);
				}
			} else {
				$count = count($this->collPayments);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Payment object to this object
	 * through the Payment foreign key attribute.
	 *
	 * @param      Payment $l Payment
	 * @return     void
	 * @throws     PropelException
	 */
	public function addPayment(Payment $l)
	{
		if ($this->collPayments === null) {
			$this->initPayments();
		}
		if (!in_array($l, $this->collPayments, true)) { // only add it if the **same** object is not already associated
			array_push($this->collPayments, $l);
			$l->setContract($this);
		}
	}

	/**
	 * Clears out the collSettlements collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addSettlements()
	 */
	public function clearSettlements()
	{
		$this->collSettlements = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collSettlements collection (array).
	 *
	 * By default this just sets the collSettlements collection to an empty array (like clearcollSettlements());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initSettlements()
	{
		$this->collSettlements = array();
	}

	/**
	 * Gets an array of Settlement objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Contract has previously been saved, it will retrieve
	 * related Settlements from storage. If this Contract is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Settlement[]
	 * @throws     PropelException
	 */
	public function getSettlements($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ContractPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSettlements === null) {
			if ($this->isNew()) {
			   $this->collSettlements = array();
			} else {

				$criteria->add(SettlementPeer::CONTRACT_ID, $this->id);

				SettlementPeer::addSelectColumns($criteria);
				$this->collSettlements = SettlementPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(SettlementPeer::CONTRACT_ID, $this->id);

				SettlementPeer::addSelectColumns($criteria);
				if (!isset($this->lastSettlementCriteria) || !$this->lastSettlementCriteria->equals($criteria)) {
					$this->collSettlements = SettlementPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastSettlementCriteria = $criteria;
		return $this->collSettlements;
	}

	/**
	 * Returns the number of related Settlement objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Settlement objects.
	 * @throws     PropelException
	 */
	public function countSettlements(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ContractPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collSettlements === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(SettlementPeer::CONTRACT_ID, $this->id);

				$count = SettlementPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(SettlementPeer::CONTRACT_ID, $this->id);

				if (!isset($this->lastSettlementCriteria) || !$this->lastSettlementCriteria->equals($criteria)) {
					$count = SettlementPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collSettlements);
				}
			} else {
				$count = count($this->collSettlements);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Settlement object to this object
	 * through the Settlement foreign key attribute.
	 *
	 * @param      Settlement $l Settlement
	 * @return     void
	 * @throws     PropelException
	 */
	public function addSettlement(Settlement $l)
	{
		if ($this->collSettlements === null) {
			$this->initSettlements();
		}
		if (!in_array($l, $this->collSettlements, true)) { // only add it if the **same** object is not already associated
			array_push($this->collSettlements, $l);
			$l->setContract($this);
		}
	}

	/**
	 * Clears out the collRegulations collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addRegulations()
	 */
	public function clearRegulations()
	{
		$this->collRegulations = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collRegulations collection (array).
	 *
	 * By default this just sets the collRegulations collection to an empty array (like clearcollRegulations());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initRegulations()
	{
		$this->collRegulations = array();
	}

	/**
	 * Gets an array of Regulation objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Contract has previously been saved, it will retrieve
	 * related Regulations from storage. If this Contract is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array Regulation[]
	 * @throws     PropelException
	 */
	public function getRegulations($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ContractPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collRegulations === null) {
			if ($this->isNew()) {
			   $this->collRegulations = array();
			} else {

				$criteria->add(RegulationPeer::CONTRACT_ID, $this->id);

				RegulationPeer::addSelectColumns($criteria);
				$this->collRegulations = RegulationPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(RegulationPeer::CONTRACT_ID, $this->id);

				RegulationPeer::addSelectColumns($criteria);
				if (!isset($this->lastRegulationCriteria) || !$this->lastRegulationCriteria->equals($criteria)) {
					$this->collRegulations = RegulationPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastRegulationCriteria = $criteria;
		return $this->collRegulations;
	}

	/**
	 * Returns the number of related Regulation objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related Regulation objects.
	 * @throws     PropelException
	 */
	public function countRegulations(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ContractPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collRegulations === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(RegulationPeer::CONTRACT_ID, $this->id);

				$count = RegulationPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(RegulationPeer::CONTRACT_ID, $this->id);

				if (!isset($this->lastRegulationCriteria) || !$this->lastRegulationCriteria->equals($criteria)) {
					$count = RegulationPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collRegulations);
				}
			} else {
				$count = count($this->collRegulations);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a Regulation object to this object
	 * through the Regulation foreign key attribute.
	 *
	 * @param      Regulation $l Regulation
	 * @return     void
	 * @throws     PropelException
	 */
	public function addRegulation(Regulation $l)
	{
		if ($this->collRegulations === null) {
			$this->initRegulations();
		}
		if (!in_array($l, $this->collRegulations, true)) { // only add it if the **same** object is not already associated
			array_push($this->collRegulations, $l);
			$l->setContract($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Contract is new, it will return
	 * an empty collection; or if this Contract has previously
	 * been saved, it will retrieve related Regulations from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Contract.
	 */
	public function getRegulationsJoinRegulationYearRelatedByRegulationYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ContractPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collRegulations === null) {
			if ($this->isNew()) {
				$this->collRegulations = array();
			} else {

				$criteria->add(RegulationPeer::CONTRACT_ID, $this->id);

				$this->collRegulations = RegulationPeer::doSelectJoinRegulationYearRelatedByRegulationYear($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(RegulationPeer::CONTRACT_ID, $this->id);

			if (!isset($this->lastRegulationCriteria) || !$this->lastRegulationCriteria->equals($criteria)) {
				$this->collRegulations = RegulationPeer::doSelectJoinRegulationYearRelatedByRegulationYear($criteria, $con, $join_behavior);
			}
		}
		$this->lastRegulationCriteria = $criteria;

		return $this->collRegulations;
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
			if ($this->collPayments) {
				foreach ((array) $this->collPayments as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collSettlements) {
				foreach ((array) $this->collSettlements as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collRegulations) {
				foreach ((array) $this->collRegulations as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collPayments = null;
		$this->collSettlements = null;
		$this->collRegulations = null;
			$this->aCreditor = null;
			$this->aCurrency = null;
	}

	// symfony_behaviors behavior
	
	/**
	 * Calls methods defined via {@link sfMixer}.
	 */
	public function __call($method, $arguments)
	{
	  if (!$callable = sfMixer::getCallable('BaseContract:'.$method))
	  {
	    throw new sfException(sprintf('Call to undefined method BaseContract::%s', $method));
	  }
	
	  array_unshift($arguments, $this);
	
	  return call_user_func_array($callable, $arguments);
	}

} // BaseContract
