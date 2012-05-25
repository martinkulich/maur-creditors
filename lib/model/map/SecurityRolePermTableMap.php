<?php


/**
 * This class defines the structure of the 'security_role_perm' table.
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
class SecurityRolePermTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.SecurityRolePermTableMap';

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
		$this->setName('security_role_perm');
		$this->setPhpName('SecurityRolePerm');
		$this->setClassname('SecurityRolePerm');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(false);
		// columns
		$this->addForeignPrimaryKey('ROLE_ID', 'RoleId', 'INTEGER' , 'security_role', 'ID', true, null, null);
		$this->addForeignPrimaryKey('PERM_ID', 'PermId', 'INTEGER' , 'security_perm', 'ID', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('SecurityRole', 'SecurityRole', RelationMap::MANY_TO_ONE, array('role_id' => 'id', ), 'CASCADE', 'CASCADE');
    $this->addRelation('SecurityPerm', 'SecurityPerm', RelationMap::MANY_TO_ONE, array('perm_id' => 'id', ), 'CASCADE', 'CASCADE');
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

} // SecurityRolePermTableMap
