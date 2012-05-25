<?php

/**
 * Base class that represents a row from the 'security_user' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseSecurityUser extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        SecurityUserPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the email field.
	 * @var        string
	 */
	protected $email;

	/**
	 * The value for the password field.
	 * @var        string
	 */
	protected $password;

	/**
	 * The value for the firstname field.
	 * @var        string
	 */
	protected $firstname;

	/**
	 * The value for the surname field.
	 * @var        string
	 */
	protected $surname;

	/**
	 * The value for the phone field.
	 * @var        string
	 */
	protected $phone;

	/**
	 * The value for the active field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $active;

	/**
	 * The value for the is_super_admin field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $is_super_admin;

	/**
	 * @var        array SecurityUserGroup[] Collection to store aggregation of SecurityUserGroup objects.
	 */
	protected $collSecurityUserGroups;

	/**
	 * @var        Criteria The criteria used to select the current contents of collSecurityUserGroups.
	 */
	private $lastSecurityUserGroupCriteria = null;

	/**
	 * @var        array SecurityUserPerm[] Collection to store aggregation of SecurityUserPerm objects.
	 */
	protected $collSecurityUserPerms;

	/**
	 * @var        Criteria The criteria used to select the current contents of collSecurityUserPerms.
	 */
	private $lastSecurityUserPermCriteria = null;

	/**
	 * @var        array SecurityUserRole[] Collection to store aggregation of SecurityUserRole objects.
	 */
	protected $collSecurityUserRoles;

	/**
	 * @var        Criteria The criteria used to select the current contents of collSecurityUserRoles.
	 */
	private $lastSecurityUserRoleCriteria = null;

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
	
	const PEER = 'SecurityUserPeer';

	/**
	 * Applies default values to this object.
	 * This method should be called from the object's constructor (or
	 * equivalent initialization method).
	 * @see        __construct()
	 */
	public function applyDefaultValues()
	{
		$this->active = true;
		$this->is_super_admin = false;
	}

	/**
	 * Initializes internal state of BaseSecurityUser object.
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
	 * Get the [email] column value.
	 * 
	 * @return     string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Get the [password] column value.
	 * 
	 * @return     string
	 */
	public function getPassword()
	{
		return $this->password;
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
	 * Get the [surname] column value.
	 * 
	 * @return     string
	 */
	public function getSurname()
	{
		return $this->surname;
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
	 * Get the [active] column value.
	 * 
	 * @return     boolean
	 */
	public function getActive()
	{
		return $this->active;
	}

	/**
	 * Get the [is_super_admin] column value.
	 * 
	 * @return     boolean
	 */
	public function getIsSuperAdmin()
	{
		return $this->is_super_admin;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     SecurityUser The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = SecurityUserPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [email] column.
	 * 
	 * @param      string $v new value
	 * @return     SecurityUser The current object (for fluent API support)
	 */
	public function setEmail($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->email !== $v) {
			$this->email = $v;
			$this->modifiedColumns[] = SecurityUserPeer::EMAIL;
		}

		return $this;
	} // setEmail()

	/**
	 * Set the value of [password] column.
	 * 
	 * @param      string $v new value
	 * @return     SecurityUser The current object (for fluent API support)
	 */
	public function setPassword($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->password !== $v) {
			$this->password = $v;
			$this->modifiedColumns[] = SecurityUserPeer::PASSWORD;
		}

		return $this;
	} // setPassword()

	/**
	 * Set the value of [firstname] column.
	 * 
	 * @param      string $v new value
	 * @return     SecurityUser The current object (for fluent API support)
	 */
	public function setFirstname($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->firstname !== $v) {
			$this->firstname = $v;
			$this->modifiedColumns[] = SecurityUserPeer::FIRSTNAME;
		}

		return $this;
	} // setFirstname()

	/**
	 * Set the value of [surname] column.
	 * 
	 * @param      string $v new value
	 * @return     SecurityUser The current object (for fluent API support)
	 */
	public function setSurname($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->surname !== $v) {
			$this->surname = $v;
			$this->modifiedColumns[] = SecurityUserPeer::SURNAME;
		}

		return $this;
	} // setSurname()

	/**
	 * Set the value of [phone] column.
	 * 
	 * @param      string $v new value
	 * @return     SecurityUser The current object (for fluent API support)
	 */
	public function setPhone($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->phone !== $v) {
			$this->phone = $v;
			$this->modifiedColumns[] = SecurityUserPeer::PHONE;
		}

		return $this;
	} // setPhone()

	/**
	 * Set the value of [active] column.
	 * 
	 * @param      boolean $v new value
	 * @return     SecurityUser The current object (for fluent API support)
	 */
	public function setActive($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->active !== $v || $this->isNew()) {
			$this->active = $v;
			$this->modifiedColumns[] = SecurityUserPeer::ACTIVE;
		}

		return $this;
	} // setActive()

	/**
	 * Set the value of [is_super_admin] column.
	 * 
	 * @param      boolean $v new value
	 * @return     SecurityUser The current object (for fluent API support)
	 */
	public function setIsSuperAdmin($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->is_super_admin !== $v || $this->isNew()) {
			$this->is_super_admin = $v;
			$this->modifiedColumns[] = SecurityUserPeer::IS_SUPER_ADMIN;
		}

		return $this;
	} // setIsSuperAdmin()

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
			if ($this->active !== true) {
				return false;
			}

			if ($this->is_super_admin !== false) {
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
			$this->email = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->password = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->firstname = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->surname = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->phone = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->active = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
			$this->is_super_admin = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 8; // 8 = SecurityUserPeer::NUM_COLUMNS - SecurityUserPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating SecurityUser object", $e);
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
			$con = Propel::getConnection(SecurityUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = SecurityUserPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collSecurityUserGroups = null;
			$this->lastSecurityUserGroupCriteria = null;

			$this->collSecurityUserPerms = null;
			$this->lastSecurityUserPermCriteria = null;

			$this->collSecurityUserRoles = null;
			$this->lastSecurityUserRoleCriteria = null;

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
			$con = Propel::getConnection(SecurityUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseSecurityUser:delete:pre') as $callable)
			{
			  if (call_user_func($callable, $this, $con))
			  {
			    $con->commit();
			
			    return;
			  }
			}

			if ($ret) {
				SecurityUserPeer::doDelete($this, $con);
				$this->postDelete($con);
				// symfony_behaviors behavior
				foreach (sfMixer::getCallables('BaseSecurityUser:delete:post') as $callable)
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
			$con = Propel::getConnection(SecurityUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseSecurityUser:save:pre') as $callable)
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
				foreach (sfMixer::getCallables('BaseSecurityUser:save:post') as $callable)
				{
				  call_user_func($callable, $this, $con, $affectedRows);
				}

				SecurityUserPeer::addInstanceToPool($this);
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
				$this->modifiedColumns[] = SecurityUserPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = SecurityUserPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += SecurityUserPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collSecurityUserGroups !== null) {
				foreach ($this->collSecurityUserGroups as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collSecurityUserPerms !== null) {
				foreach ($this->collSecurityUserPerms as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collSecurityUserRoles !== null) {
				foreach ($this->collSecurityUserRoles as $referrerFK) {
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


			if (($retval = SecurityUserPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collSecurityUserGroups !== null) {
					foreach ($this->collSecurityUserGroups as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collSecurityUserPerms !== null) {
					foreach ($this->collSecurityUserPerms as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collSecurityUserRoles !== null) {
					foreach ($this->collSecurityUserRoles as $referrerFK) {
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
		$pos = SecurityUserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getEmail();
				break;
			case 2:
				return $this->getPassword();
				break;
			case 3:
				return $this->getFirstname();
				break;
			case 4:
				return $this->getSurname();
				break;
			case 5:
				return $this->getPhone();
				break;
			case 6:
				return $this->getActive();
				break;
			case 7:
				return $this->getIsSuperAdmin();
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
		$keys = SecurityUserPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getEmail(),
			$keys[2] => $this->getPassword(),
			$keys[3] => $this->getFirstname(),
			$keys[4] => $this->getSurname(),
			$keys[5] => $this->getPhone(),
			$keys[6] => $this->getActive(),
			$keys[7] => $this->getIsSuperAdmin(),
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
		$pos = SecurityUserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setEmail($value);
				break;
			case 2:
				$this->setPassword($value);
				break;
			case 3:
				$this->setFirstname($value);
				break;
			case 4:
				$this->setSurname($value);
				break;
			case 5:
				$this->setPhone($value);
				break;
			case 6:
				$this->setActive($value);
				break;
			case 7:
				$this->setIsSuperAdmin($value);
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
		$keys = SecurityUserPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setEmail($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setPassword($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setFirstname($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setSurname($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setPhone($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setActive($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setIsSuperAdmin($arr[$keys[7]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(SecurityUserPeer::DATABASE_NAME);

		if ($this->isColumnModified(SecurityUserPeer::ID)) $criteria->add(SecurityUserPeer::ID, $this->id);
		if ($this->isColumnModified(SecurityUserPeer::EMAIL)) $criteria->add(SecurityUserPeer::EMAIL, $this->email);
		if ($this->isColumnModified(SecurityUserPeer::PASSWORD)) $criteria->add(SecurityUserPeer::PASSWORD, $this->password);
		if ($this->isColumnModified(SecurityUserPeer::FIRSTNAME)) $criteria->add(SecurityUserPeer::FIRSTNAME, $this->firstname);
		if ($this->isColumnModified(SecurityUserPeer::SURNAME)) $criteria->add(SecurityUserPeer::SURNAME, $this->surname);
		if ($this->isColumnModified(SecurityUserPeer::PHONE)) $criteria->add(SecurityUserPeer::PHONE, $this->phone);
		if ($this->isColumnModified(SecurityUserPeer::ACTIVE)) $criteria->add(SecurityUserPeer::ACTIVE, $this->active);
		if ($this->isColumnModified(SecurityUserPeer::IS_SUPER_ADMIN)) $criteria->add(SecurityUserPeer::IS_SUPER_ADMIN, $this->is_super_admin);

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
		$criteria = new Criteria(SecurityUserPeer::DATABASE_NAME);

		$criteria->add(SecurityUserPeer::ID, $this->id);

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
	 * @param      object $copyObj An object of SecurityUser (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setEmail($this->email);

		$copyObj->setPassword($this->password);

		$copyObj->setFirstname($this->firstname);

		$copyObj->setSurname($this->surname);

		$copyObj->setPhone($this->phone);

		$copyObj->setActive($this->active);

		$copyObj->setIsSuperAdmin($this->is_super_admin);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getSecurityUserGroups() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addSecurityUserGroup($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getSecurityUserPerms() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addSecurityUserPerm($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getSecurityUserRoles() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addSecurityUserRole($relObj->copy($deepCopy));
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
	 * @return     SecurityUser Clone of current object.
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
	 * @return     SecurityUserPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new SecurityUserPeer();
		}
		return self::$peer;
	}

	/**
	 * Clears out the collSecurityUserGroups collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addSecurityUserGroups()
	 */
	public function clearSecurityUserGroups()
	{
		$this->collSecurityUserGroups = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collSecurityUserGroups collection (array).
	 *
	 * By default this just sets the collSecurityUserGroups collection to an empty array (like clearcollSecurityUserGroups());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initSecurityUserGroups()
	{
		$this->collSecurityUserGroups = array();
	}

	/**
	 * Gets an array of SecurityUserGroup objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this SecurityUser has previously been saved, it will retrieve
	 * related SecurityUserGroups from storage. If this SecurityUser is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array SecurityUserGroup[]
	 * @throws     PropelException
	 */
	public function getSecurityUserGroups($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SecurityUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSecurityUserGroups === null) {
			if ($this->isNew()) {
			   $this->collSecurityUserGroups = array();
			} else {

				$criteria->add(SecurityUserGroupPeer::USER_ID, $this->id);

				SecurityUserGroupPeer::addSelectColumns($criteria);
				$this->collSecurityUserGroups = SecurityUserGroupPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(SecurityUserGroupPeer::USER_ID, $this->id);

				SecurityUserGroupPeer::addSelectColumns($criteria);
				if (!isset($this->lastSecurityUserGroupCriteria) || !$this->lastSecurityUserGroupCriteria->equals($criteria)) {
					$this->collSecurityUserGroups = SecurityUserGroupPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastSecurityUserGroupCriteria = $criteria;
		return $this->collSecurityUserGroups;
	}

	/**
	 * Returns the number of related SecurityUserGroup objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related SecurityUserGroup objects.
	 * @throws     PropelException
	 */
	public function countSecurityUserGroups(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SecurityUserPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collSecurityUserGroups === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(SecurityUserGroupPeer::USER_ID, $this->id);

				$count = SecurityUserGroupPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(SecurityUserGroupPeer::USER_ID, $this->id);

				if (!isset($this->lastSecurityUserGroupCriteria) || !$this->lastSecurityUserGroupCriteria->equals($criteria)) {
					$count = SecurityUserGroupPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collSecurityUserGroups);
				}
			} else {
				$count = count($this->collSecurityUserGroups);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a SecurityUserGroup object to this object
	 * through the SecurityUserGroup foreign key attribute.
	 *
	 * @param      SecurityUserGroup $l SecurityUserGroup
	 * @return     void
	 * @throws     PropelException
	 */
	public function addSecurityUserGroup(SecurityUserGroup $l)
	{
		if ($this->collSecurityUserGroups === null) {
			$this->initSecurityUserGroups();
		}
		if (!in_array($l, $this->collSecurityUserGroups, true)) { // only add it if the **same** object is not already associated
			array_push($this->collSecurityUserGroups, $l);
			$l->setSecurityUser($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this SecurityUser is new, it will return
	 * an empty collection; or if this SecurityUser has previously
	 * been saved, it will retrieve related SecurityUserGroups from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in SecurityUser.
	 */
	public function getSecurityUserGroupsJoinSecurityGroup($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SecurityUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSecurityUserGroups === null) {
			if ($this->isNew()) {
				$this->collSecurityUserGroups = array();
			} else {

				$criteria->add(SecurityUserGroupPeer::USER_ID, $this->id);

				$this->collSecurityUserGroups = SecurityUserGroupPeer::doSelectJoinSecurityGroup($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(SecurityUserGroupPeer::USER_ID, $this->id);

			if (!isset($this->lastSecurityUserGroupCriteria) || !$this->lastSecurityUserGroupCriteria->equals($criteria)) {
				$this->collSecurityUserGroups = SecurityUserGroupPeer::doSelectJoinSecurityGroup($criteria, $con, $join_behavior);
			}
		}
		$this->lastSecurityUserGroupCriteria = $criteria;

		return $this->collSecurityUserGroups;
	}

	/**
	 * Clears out the collSecurityUserPerms collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addSecurityUserPerms()
	 */
	public function clearSecurityUserPerms()
	{
		$this->collSecurityUserPerms = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collSecurityUserPerms collection (array).
	 *
	 * By default this just sets the collSecurityUserPerms collection to an empty array (like clearcollSecurityUserPerms());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initSecurityUserPerms()
	{
		$this->collSecurityUserPerms = array();
	}

	/**
	 * Gets an array of SecurityUserPerm objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this SecurityUser has previously been saved, it will retrieve
	 * related SecurityUserPerms from storage. If this SecurityUser is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array SecurityUserPerm[]
	 * @throws     PropelException
	 */
	public function getSecurityUserPerms($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SecurityUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSecurityUserPerms === null) {
			if ($this->isNew()) {
			   $this->collSecurityUserPerms = array();
			} else {

				$criteria->add(SecurityUserPermPeer::USER_ID, $this->id);

				SecurityUserPermPeer::addSelectColumns($criteria);
				$this->collSecurityUserPerms = SecurityUserPermPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(SecurityUserPermPeer::USER_ID, $this->id);

				SecurityUserPermPeer::addSelectColumns($criteria);
				if (!isset($this->lastSecurityUserPermCriteria) || !$this->lastSecurityUserPermCriteria->equals($criteria)) {
					$this->collSecurityUserPerms = SecurityUserPermPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastSecurityUserPermCriteria = $criteria;
		return $this->collSecurityUserPerms;
	}

	/**
	 * Returns the number of related SecurityUserPerm objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related SecurityUserPerm objects.
	 * @throws     PropelException
	 */
	public function countSecurityUserPerms(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SecurityUserPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collSecurityUserPerms === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(SecurityUserPermPeer::USER_ID, $this->id);

				$count = SecurityUserPermPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(SecurityUserPermPeer::USER_ID, $this->id);

				if (!isset($this->lastSecurityUserPermCriteria) || !$this->lastSecurityUserPermCriteria->equals($criteria)) {
					$count = SecurityUserPermPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collSecurityUserPerms);
				}
			} else {
				$count = count($this->collSecurityUserPerms);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a SecurityUserPerm object to this object
	 * through the SecurityUserPerm foreign key attribute.
	 *
	 * @param      SecurityUserPerm $l SecurityUserPerm
	 * @return     void
	 * @throws     PropelException
	 */
	public function addSecurityUserPerm(SecurityUserPerm $l)
	{
		if ($this->collSecurityUserPerms === null) {
			$this->initSecurityUserPerms();
		}
		if (!in_array($l, $this->collSecurityUserPerms, true)) { // only add it if the **same** object is not already associated
			array_push($this->collSecurityUserPerms, $l);
			$l->setSecurityUser($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this SecurityUser is new, it will return
	 * an empty collection; or if this SecurityUser has previously
	 * been saved, it will retrieve related SecurityUserPerms from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in SecurityUser.
	 */
	public function getSecurityUserPermsJoinSecurityPerm($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SecurityUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSecurityUserPerms === null) {
			if ($this->isNew()) {
				$this->collSecurityUserPerms = array();
			} else {

				$criteria->add(SecurityUserPermPeer::USER_ID, $this->id);

				$this->collSecurityUserPerms = SecurityUserPermPeer::doSelectJoinSecurityPerm($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(SecurityUserPermPeer::USER_ID, $this->id);

			if (!isset($this->lastSecurityUserPermCriteria) || !$this->lastSecurityUserPermCriteria->equals($criteria)) {
				$this->collSecurityUserPerms = SecurityUserPermPeer::doSelectJoinSecurityPerm($criteria, $con, $join_behavior);
			}
		}
		$this->lastSecurityUserPermCriteria = $criteria;

		return $this->collSecurityUserPerms;
	}

	/**
	 * Clears out the collSecurityUserRoles collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addSecurityUserRoles()
	 */
	public function clearSecurityUserRoles()
	{
		$this->collSecurityUserRoles = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collSecurityUserRoles collection (array).
	 *
	 * By default this just sets the collSecurityUserRoles collection to an empty array (like clearcollSecurityUserRoles());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initSecurityUserRoles()
	{
		$this->collSecurityUserRoles = array();
	}

	/**
	 * Gets an array of SecurityUserRole objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this SecurityUser has previously been saved, it will retrieve
	 * related SecurityUserRoles from storage. If this SecurityUser is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array SecurityUserRole[]
	 * @throws     PropelException
	 */
	public function getSecurityUserRoles($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SecurityUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSecurityUserRoles === null) {
			if ($this->isNew()) {
			   $this->collSecurityUserRoles = array();
			} else {

				$criteria->add(SecurityUserRolePeer::USER_ID, $this->id);

				SecurityUserRolePeer::addSelectColumns($criteria);
				$this->collSecurityUserRoles = SecurityUserRolePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(SecurityUserRolePeer::USER_ID, $this->id);

				SecurityUserRolePeer::addSelectColumns($criteria);
				if (!isset($this->lastSecurityUserRoleCriteria) || !$this->lastSecurityUserRoleCriteria->equals($criteria)) {
					$this->collSecurityUserRoles = SecurityUserRolePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastSecurityUserRoleCriteria = $criteria;
		return $this->collSecurityUserRoles;
	}

	/**
	 * Returns the number of related SecurityUserRole objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related SecurityUserRole objects.
	 * @throws     PropelException
	 */
	public function countSecurityUserRoles(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SecurityUserPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collSecurityUserRoles === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(SecurityUserRolePeer::USER_ID, $this->id);

				$count = SecurityUserRolePeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(SecurityUserRolePeer::USER_ID, $this->id);

				if (!isset($this->lastSecurityUserRoleCriteria) || !$this->lastSecurityUserRoleCriteria->equals($criteria)) {
					$count = SecurityUserRolePeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collSecurityUserRoles);
				}
			} else {
				$count = count($this->collSecurityUserRoles);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a SecurityUserRole object to this object
	 * through the SecurityUserRole foreign key attribute.
	 *
	 * @param      SecurityUserRole $l SecurityUserRole
	 * @return     void
	 * @throws     PropelException
	 */
	public function addSecurityUserRole(SecurityUserRole $l)
	{
		if ($this->collSecurityUserRoles === null) {
			$this->initSecurityUserRoles();
		}
		if (!in_array($l, $this->collSecurityUserRoles, true)) { // only add it if the **same** object is not already associated
			array_push($this->collSecurityUserRoles, $l);
			$l->setSecurityUser($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this SecurityUser is new, it will return
	 * an empty collection; or if this SecurityUser has previously
	 * been saved, it will retrieve related SecurityUserRoles from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in SecurityUser.
	 */
	public function getSecurityUserRolesJoinSecurityRole($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(SecurityUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collSecurityUserRoles === null) {
			if ($this->isNew()) {
				$this->collSecurityUserRoles = array();
			} else {

				$criteria->add(SecurityUserRolePeer::USER_ID, $this->id);

				$this->collSecurityUserRoles = SecurityUserRolePeer::doSelectJoinSecurityRole($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(SecurityUserRolePeer::USER_ID, $this->id);

			if (!isset($this->lastSecurityUserRoleCriteria) || !$this->lastSecurityUserRoleCriteria->equals($criteria)) {
				$this->collSecurityUserRoles = SecurityUserRolePeer::doSelectJoinSecurityRole($criteria, $con, $join_behavior);
			}
		}
		$this->lastSecurityUserRoleCriteria = $criteria;

		return $this->collSecurityUserRoles;
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
			if ($this->collSecurityUserGroups) {
				foreach ((array) $this->collSecurityUserGroups as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collSecurityUserPerms) {
				foreach ((array) $this->collSecurityUserPerms as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collSecurityUserRoles) {
				foreach ((array) $this->collSecurityUserRoles as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collSecurityUserGroups = null;
		$this->collSecurityUserPerms = null;
		$this->collSecurityUserRoles = null;
	}

	// symfony_behaviors behavior
	
	/**
	 * Calls methods defined via {@link sfMixer}.
	 */
	public function __call($method, $arguments)
	{
	  if (!$callable = sfMixer::getCallable('BaseSecurityUser:'.$method))
	  {
	    throw new sfException(sprintf('Call to undefined method BaseSecurityUser::%s', $method));
	  }
	
	  array_unshift($arguments, $this);
	
	  return call_user_func_array($callable, $arguments);
	}

} // BaseSecurityUser
