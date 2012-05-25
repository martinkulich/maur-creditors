<?php


/**
 * This class defines the structure of the 'security_user' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class SecurityUserTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.SecurityUserTableMap';

	/**
	 * Initialize the table attributes, columns and validators
	 * Relations are not initialized by this method since they are lazy loaded
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function initialize()
	{
	  // attributes
		$this->setName('security_user');
		$this->setPhpName('SecurityUser');
		$this->setClassname('SecurityUser');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(true);
		$this->setPrimaryKeyMethodInfo('security_user_id_seq');
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addColumn('EMAIL', 'Email', 'VARCHAR', true, 255, null);
		$this->addColumn('PASSWORD', 'Password', 'VARCHAR', true, 255, null);
		$this->addColumn('FIRSTNAME', 'Firstname', 'VARCHAR', true, 255, null);
		$this->addColumn('SURNAME', 'Surname', 'VARCHAR', true, 255, null);
		$this->addColumn('PHONE', 'Phone', 'VARCHAR', false, 255, null);
		$this->addColumn('ACTIVE', 'Active', 'BOOLEAN', true, null, true);
		$this->addColumn('IS_SUPER_ADMIN', 'IsSuperAdmin', 'BOOLEAN', true, null, false);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('SecurityUserGroup', 'SecurityUserGroup', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', 'CASCADE');
    $this->addRelation('SecurityUserPerm', 'SecurityUserPerm', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', 'CASCADE');
    $this->addRelation('SecurityUserRole', 'SecurityUserRole', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', 'CASCADE');
	} // buildRelations()

	/**
	 * 
	 * Gets the list of behaviors registered for this table
	 * 
	 * @return array Associative array (name => parameters) of behaviors
	 */
	public function getBehaviors()
	{
		return array(
			'symfony' => array('form' => 'true', 'filter' => 'true', ),
			'symfony_behaviors' => array(),
		);
	} // getBehaviors()

} // SecurityUserTableMap
