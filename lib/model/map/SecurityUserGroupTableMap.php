<?php


/**
 * This class defines the structure of the 'security_user_group' table.
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
class SecurityUserGroupTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.SecurityUserGroupTableMap';

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
		$this->setName('security_user_group');
		$this->setPhpName('SecurityUserGroup');
		$this->setClassname('SecurityUserGroup');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(false);
		// columns
		$this->addForeignPrimaryKey('USER_ID', 'UserId', 'INTEGER' , 'security_user', 'ID', true, null, null);
		$this->addForeignPrimaryKey('GROUP_ID', 'GroupId', 'INTEGER' , 'security_group', 'ID', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('SecurityGroup', 'SecurityGroup', RelationMap::MANY_TO_ONE, array('group_id' => 'id', ), 'CASCADE', 'CASCADE');
    $this->addRelation('SecurityUser', 'SecurityUser', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'CASCADE', 'CASCADE');
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

} // SecurityUserGroupTableMap
