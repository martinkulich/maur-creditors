<?php

/**
 * Base class that represents a row from the 'outgoing_payment' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseOutgoingPayment extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        OutgoingPaymentPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the bank_account_id field.
	 * @var        int
	 */
	protected $bank_account_id;

	/**
	 * The value for the amount field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $amount;

	/**
	 * The value for the date field.
	 * @var        string
	 */
	protected $date;

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
	 * The value for the creditor_id field.
	 * @var        int
	 */
	protected $creditor_id;

	/**
	 * The value for the cash field.
	 * @var        boolean
	 */
	protected $cash;

	/**
	 * The value for the receiver_bank_account field.
	 * @var        string
	 */
	protected $receiver_bank_account;

	/**
	 * The value for the refundation field.
	 * @var        string
	 */
	protected $refundation;

	/**
	 * @var        Subject
	 */
	protected $aSubject;

	/**
	 * @var        Currency
	 */
	protected $aCurrency;

	/**
	 * @var        BankAccount
	 */
	protected $aBankAccount;

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
	
	const PEER = 'OutgoingPaymentPeer';

	/**
	 * Applies default values to this object.
	 * This method should be called from the object's constructor (or
	 * equivalent initialization method).
	 * @see        __construct()
	 */
	public function applyDefaultValues()
	{
		$this->amount = '0';
	}

	/**
	 * Initializes internal state of BaseOutgoingPayment object.
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
	 * Get the [bank_account_id] column value.
	 * 
	 * @return     int
	 */
	public function getBankAccountId()
	{
		return $this->bank_account_id;
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
	 * Get the [creditor_id] column value.
	 * 
	 * @return     int
	 */
	public function getCreditorId()
	{
		return $this->creditor_id;
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
	 * Get the [receiver_bank_account] column value.
	 * 
	 * @return     string
	 */
	public function getReceiverBankAccount()
	{
		return $this->receiver_bank_account;
	}

	/**
	 * Get the [refundation] column value.
	 * 
	 * @return     string
	 */
	public function getRefundation()
	{
		return $this->refundation;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     OutgoingPayment The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = OutgoingPaymentPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [bank_account_id] column.
	 * 
	 * @param      int $v new value
	 * @return     OutgoingPayment The current object (for fluent API support)
	 */
	public function setBankAccountId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->bank_account_id !== $v) {
			$this->bank_account_id = $v;
			$this->modifiedColumns[] = OutgoingPaymentPeer::BANK_ACCOUNT_ID;
		}

		if ($this->aBankAccount !== null && $this->aBankAccount->getId() !== $v) {
			$this->aBankAccount = null;
		}

		return $this;
	} // setBankAccountId()

	/**
	 * Set the value of [amount] column.
	 * 
	 * @param      string $v new value
	 * @return     OutgoingPayment The current object (for fluent API support)
	 */
	public function setAmount($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->amount !== $v || $this->isNew()) {
			$this->amount = $v;
			$this->modifiedColumns[] = OutgoingPaymentPeer::AMOUNT;
		}

		return $this;
	} // setAmount()

	/**
	 * Sets the value of [date] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     OutgoingPayment The current object (for fluent API support)
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
				$this->modifiedColumns[] = OutgoingPaymentPeer::DATE;
			}
		} // if either are not null

		return $this;
	} // setDate()

	/**
	 * Set the value of [note] column.
	 * 
	 * @param      string $v new value
	 * @return     OutgoingPayment The current object (for fluent API support)
	 */
	public function setNote($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->note !== $v) {
			$this->note = $v;
			$this->modifiedColumns[] = OutgoingPaymentPeer::NOTE;
		}

		return $this;
	} // setNote()

	/**
	 * Set the value of [currency_code] column.
	 * 
	 * @param      string $v new value
	 * @return     OutgoingPayment The current object (for fluent API support)
	 */
	public function setCurrencyCode($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->currency_code !== $v) {
			$this->currency_code = $v;
			$this->modifiedColumns[] = OutgoingPaymentPeer::CURRENCY_CODE;
		}

		if ($this->aCurrency !== null && $this->aCurrency->getCode() !== $v) {
			$this->aCurrency = null;
		}

		return $this;
	} // setCurrencyCode()

	/**
	 * Set the value of [creditor_id] column.
	 * 
	 * @param      int $v new value
	 * @return     OutgoingPayment The current object (for fluent API support)
	 */
	public function setCreditorId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->creditor_id !== $v) {
			$this->creditor_id = $v;
			$this->modifiedColumns[] = OutgoingPaymentPeer::CREDITOR_ID;
		}

		if ($this->aSubject !== null && $this->aSubject->getId() !== $v) {
			$this->aSubject = null;
		}

		return $this;
	} // setCreditorId()

	/**
	 * Set the value of [cash] column.
	 * 
	 * @param      boolean $v new value
	 * @return     OutgoingPayment The current object (for fluent API support)
	 */
	public function setCash($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->cash !== $v) {
			$this->cash = $v;
			$this->modifiedColumns[] = OutgoingPaymentPeer::CASH;
		}

		return $this;
	} // setCash()

	/**
	 * Set the value of [receiver_bank_account] column.
	 * 
	 * @param      string $v new value
	 * @return     OutgoingPayment The current object (for fluent API support)
	 */
	public function setReceiverBankAccount($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->receiver_bank_account !== $v) {
			$this->receiver_bank_account = $v;
			$this->modifiedColumns[] = OutgoingPaymentPeer::RECEIVER_BANK_ACCOUNT;
		}

		return $this;
	} // setReceiverBankAccount()

	/**
	 * Set the value of [refundation] column.
	 * 
	 * @param      string $v new value
	 * @return     OutgoingPayment The current object (for fluent API support)
	 */
	public function setRefundation($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->refundation !== $v) {
			$this->refundation = $v;
			$this->modifiedColumns[] = OutgoingPaymentPeer::REFUNDATION;
		}

		return $this;
	} // setRefundation()

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
			if ($this->amount !== '0') {
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
			$this->bank_account_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->amount = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->date = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->note = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->currency_code = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->creditor_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
			$this->cash = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
			$this->receiver_bank_account = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->refundation = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 10; // 10 = OutgoingPaymentPeer::NUM_COLUMNS - OutgoingPaymentPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating OutgoingPayment object", $e);
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

		if ($this->aBankAccount !== null && $this->bank_account_id !== $this->aBankAccount->getId()) {
			$this->aBankAccount = null;
		}
		if ($this->aCurrency !== null && $this->currency_code !== $this->aCurrency->getCode()) {
			$this->aCurrency = null;
		}
		if ($this->aSubject !== null && $this->creditor_id !== $this->aSubject->getId()) {
			$this->aSubject = null;
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
			$con = Propel::getConnection(OutgoingPaymentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = OutgoingPaymentPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aSubject = null;
			$this->aCurrency = null;
			$this->aBankAccount = null;
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
			$con = Propel::getConnection(OutgoingPaymentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseOutgoingPayment:delete:pre') as $callable)
			{
			  if (call_user_func($callable, $this, $con))
			  {
			    $con->commit();
			
			    return;
			  }
			}

			if ($ret) {
				OutgoingPaymentPeer::doDelete($this, $con);
				$this->postDelete($con);
				// symfony_behaviors behavior
				foreach (sfMixer::getCallables('BaseOutgoingPayment:delete:post') as $callable)
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
			$con = Propel::getConnection(OutgoingPaymentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseOutgoingPayment:save:pre') as $callable)
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
				foreach (sfMixer::getCallables('BaseOutgoingPayment:save:post') as $callable)
				{
				  call_user_func($callable, $this, $con, $affectedRows);
				}

				OutgoingPaymentPeer::addInstanceToPool($this);
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

			if ($this->aSubject !== null) {
				if ($this->aSubject->isModified() || $this->aSubject->isNew()) {
					$affectedRows += $this->aSubject->save($con);
				}
				$this->setSubject($this->aSubject);
			}

			if ($this->aCurrency !== null) {
				if ($this->aCurrency->isModified() || $this->aCurrency->isNew()) {
					$affectedRows += $this->aCurrency->save($con);
				}
				$this->setCurrency($this->aCurrency);
			}

			if ($this->aBankAccount !== null) {
				if ($this->aBankAccount->isModified() || $this->aBankAccount->isNew()) {
					$affectedRows += $this->aBankAccount->save($con);
				}
				$this->setBankAccount($this->aBankAccount);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = OutgoingPaymentPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = OutgoingPaymentPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += OutgoingPaymentPeer::doUpdate($this, $con);
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

			if ($this->aSubject !== null) {
				if (!$this->aSubject->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aSubject->getValidationFailures());
				}
			}

			if ($this->aCurrency !== null) {
				if (!$this->aCurrency->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aCurrency->getValidationFailures());
				}
			}

			if ($this->aBankAccount !== null) {
				if (!$this->aBankAccount->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aBankAccount->getValidationFailures());
				}
			}


			if (($retval = OutgoingPaymentPeer::doValidate($this, $columns)) !== true) {
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
		$pos = OutgoingPaymentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getBankAccountId();
				break;
			case 2:
				return $this->getAmount();
				break;
			case 3:
				return $this->getDate();
				break;
			case 4:
				return $this->getNote();
				break;
			case 5:
				return $this->getCurrencyCode();
				break;
			case 6:
				return $this->getCreditorId();
				break;
			case 7:
				return $this->getCash();
				break;
			case 8:
				return $this->getReceiverBankAccount();
				break;
			case 9:
				return $this->getRefundation();
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
		$keys = OutgoingPaymentPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getBankAccountId(),
			$keys[2] => $this->getAmount(),
			$keys[3] => $this->getDate(),
			$keys[4] => $this->getNote(),
			$keys[5] => $this->getCurrencyCode(),
			$keys[6] => $this->getCreditorId(),
			$keys[7] => $this->getCash(),
			$keys[8] => $this->getReceiverBankAccount(),
			$keys[9] => $this->getRefundation(),
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
		$pos = OutgoingPaymentPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setBankAccountId($value);
				break;
			case 2:
				$this->setAmount($value);
				break;
			case 3:
				$this->setDate($value);
				break;
			case 4:
				$this->setNote($value);
				break;
			case 5:
				$this->setCurrencyCode($value);
				break;
			case 6:
				$this->setCreditorId($value);
				break;
			case 7:
				$this->setCash($value);
				break;
			case 8:
				$this->setReceiverBankAccount($value);
				break;
			case 9:
				$this->setRefundation($value);
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
		$keys = OutgoingPaymentPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setBankAccountId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setAmount($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setDate($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setNote($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setCurrencyCode($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setCreditorId($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setCash($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setReceiverBankAccount($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setRefundation($arr[$keys[9]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(OutgoingPaymentPeer::DATABASE_NAME);

		if ($this->isColumnModified(OutgoingPaymentPeer::ID)) $criteria->add(OutgoingPaymentPeer::ID, $this->id);
		if ($this->isColumnModified(OutgoingPaymentPeer::BANK_ACCOUNT_ID)) $criteria->add(OutgoingPaymentPeer::BANK_ACCOUNT_ID, $this->bank_account_id);
		if ($this->isColumnModified(OutgoingPaymentPeer::AMOUNT)) $criteria->add(OutgoingPaymentPeer::AMOUNT, $this->amount);
		if ($this->isColumnModified(OutgoingPaymentPeer::DATE)) $criteria->add(OutgoingPaymentPeer::DATE, $this->date);
		if ($this->isColumnModified(OutgoingPaymentPeer::NOTE)) $criteria->add(OutgoingPaymentPeer::NOTE, $this->note);
		if ($this->isColumnModified(OutgoingPaymentPeer::CURRENCY_CODE)) $criteria->add(OutgoingPaymentPeer::CURRENCY_CODE, $this->currency_code);
		if ($this->isColumnModified(OutgoingPaymentPeer::CREDITOR_ID)) $criteria->add(OutgoingPaymentPeer::CREDITOR_ID, $this->creditor_id);
		if ($this->isColumnModified(OutgoingPaymentPeer::CASH)) $criteria->add(OutgoingPaymentPeer::CASH, $this->cash);
		if ($this->isColumnModified(OutgoingPaymentPeer::RECEIVER_BANK_ACCOUNT)) $criteria->add(OutgoingPaymentPeer::RECEIVER_BANK_ACCOUNT, $this->receiver_bank_account);
		if ($this->isColumnModified(OutgoingPaymentPeer::REFUNDATION)) $criteria->add(OutgoingPaymentPeer::REFUNDATION, $this->refundation);

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
		$criteria = new Criteria(OutgoingPaymentPeer::DATABASE_NAME);

		$criteria->add(OutgoingPaymentPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of OutgoingPayment (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setBankAccountId($this->bank_account_id);

		$copyObj->setAmount($this->amount);

		$copyObj->setDate($this->date);

		$copyObj->setNote($this->note);

		$copyObj->setCurrencyCode($this->currency_code);

		$copyObj->setCreditorId($this->creditor_id);

		$copyObj->setCash($this->cash);

		$copyObj->setReceiverBankAccount($this->receiver_bank_account);

		$copyObj->setRefundation($this->refundation);


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
	 * @return     OutgoingPayment Clone of current object.
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
	 * @return     OutgoingPaymentPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new OutgoingPaymentPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Subject object.
	 *
	 * @param      Subject $v
	 * @return     OutgoingPayment The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setSubject(Subject $v = null)
	{
		if ($v === null) {
			$this->setCreditorId(NULL);
		} else {
			$this->setCreditorId($v->getId());
		}

		$this->aSubject = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Subject object, it will not be re-added.
		if ($v !== null) {
			$v->addOutgoingPayment($this);
		}

		return $this;
	}


	/**
	 * Get the associated Subject object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Subject The associated Subject object.
	 * @throws     PropelException
	 */
	public function getSubject(PropelPDO $con = null)
	{
		if ($this->aSubject === null && ($this->creditor_id !== null)) {
			$this->aSubject = SubjectPeer::retrieveByPk($this->creditor_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aSubject->addOutgoingPayments($this);
			 */
		}
		return $this->aSubject;
	}

	/**
	 * Declares an association between this object and a Currency object.
	 *
	 * @param      Currency $v
	 * @return     OutgoingPayment The current object (for fluent API support)
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
			$v->addOutgoingPayment($this);
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
			   $this->aCurrency->addOutgoingPayments($this);
			 */
		}
		return $this->aCurrency;
	}

	/**
	 * Declares an association between this object and a BankAccount object.
	 *
	 * @param      BankAccount $v
	 * @return     OutgoingPayment The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setBankAccount(BankAccount $v = null)
	{
		if ($v === null) {
			$this->setBankAccountId(NULL);
		} else {
			$this->setBankAccountId($v->getId());
		}

		$this->aBankAccount = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the BankAccount object, it will not be re-added.
		if ($v !== null) {
			$v->addOutgoingPayment($this);
		}

		return $this;
	}


	/**
	 * Get the associated BankAccount object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     BankAccount The associated BankAccount object.
	 * @throws     PropelException
	 */
	public function getBankAccount(PropelPDO $con = null)
	{
		if ($this->aBankAccount === null && ($this->bank_account_id !== null)) {
			$this->aBankAccount = BankAccountPeer::retrieveByPk($this->bank_account_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aBankAccount->addOutgoingPayments($this);
			 */
		}
		return $this->aBankAccount;
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
	 * Otherwise if this OutgoingPayment has previously been saved, it will retrieve
	 * related Allocations from storage. If this OutgoingPayment is new, it will return
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
			$criteria = new Criteria(OutgoingPaymentPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collAllocations === null) {
			if ($this->isNew()) {
			   $this->collAllocations = array();
			} else {

				$criteria->add(AllocationPeer::OUTGOING_PAYMENT_ID, $this->id);

				AllocationPeer::addSelectColumns($criteria);
				$this->collAllocations = AllocationPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(AllocationPeer::OUTGOING_PAYMENT_ID, $this->id);

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
			$criteria = new Criteria(OutgoingPaymentPeer::DATABASE_NAME);
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

				$criteria->add(AllocationPeer::OUTGOING_PAYMENT_ID, $this->id);

				$count = AllocationPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(AllocationPeer::OUTGOING_PAYMENT_ID, $this->id);

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
			$l->setOutgoingPayment($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this OutgoingPayment is new, it will return
	 * an empty collection; or if this OutgoingPayment has previously
	 * been saved, it will retrieve related Allocations from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in OutgoingPayment.
	 */
	public function getAllocationsJoinSettlement($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(OutgoingPaymentPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collAllocations === null) {
			if ($this->isNew()) {
				$this->collAllocations = array();
			} else {

				$criteria->add(AllocationPeer::OUTGOING_PAYMENT_ID, $this->id);

				$this->collAllocations = AllocationPeer::doSelectJoinSettlement($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(AllocationPeer::OUTGOING_PAYMENT_ID, $this->id);

			if (!isset($this->lastAllocationCriteria) || !$this->lastAllocationCriteria->equals($criteria)) {
				$this->collAllocations = AllocationPeer::doSelectJoinSettlement($criteria, $con, $join_behavior);
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
			$this->aSubject = null;
			$this->aCurrency = null;
			$this->aBankAccount = null;
	}

	// symfony_behaviors behavior
	
	/**
	 * Calls methods defined via {@link sfMixer}.
	 */
	public function __call($method, $arguments)
	{
	  if (!$callable = sfMixer::getCallable('BaseOutgoingPayment:'.$method))
	  {
	    throw new sfException(sprintf('Call to undefined method BaseOutgoingPayment::%s', $method));
	  }
	
	  array_unshift($arguments, $this);
	
	  return call_user_func_array($callable, $arguments);
	}

} // BaseOutgoingPayment
