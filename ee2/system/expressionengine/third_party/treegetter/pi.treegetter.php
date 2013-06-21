<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name'        => 'Taxonomy Treegetter',
	'pi_version'     => '1.0',
	'pi_author'      => 'Jonathan W. Kelly, Paramore - the digital agency',
	'pi_author_url'  => 'http://github.com/jonathanwkelly/ee2-treegetter',
	'pi_description' => 'Will determine which Taxonomy tree should be in scope.',
	'pi_usage'       => 'See http://github.com/jonathanwkelly/ee2-treegetter'
);

/**
 * Treegetter Plugin class
 *
 * @package        treegetter
 * @author         Jonathan W. Kelly, Paramore - the digital agency
 * @link           http://github.com/jonathanwkelly/ee2-treegetter
 * @license        http://creativecommons.org/licenses/by-sa/3.0/
 */
class Treegetter {

	public $return_data;

	// --------------------------------------------------------------------

	/**
	 * @return {integer}
	 */
	public function __construct()
	{
		/* Which segment should we eval? Default to first... */
		$segment = 1;
		if(ee()->TMPL->fetch_param("segment"))
			$segment = (int) ee()->TMPL->fetch_param("segment");

		$this->return_data = $this->get_tree_by_segment($segment, (int) ee()->TMPL->fetch_param("default_tree_id"));

		return $this->return_data;
	}

	// --------------------------------------------------------------------

	/**
	 * @param $segment {integer}
	 * @param $default_tree_id {integer}
	 * @return {integer}
	 */
	private function get_tree_by_segment($segment=1, $default_tree_id=0)
	{
		ee()->db->select('taxonomy_trees.id AS tree_id');
		ee()->db->from('taxonomy_trees');
		ee()->db->where('channels.channel_name', ee()->uri->segment($segment));
		ee()->db->join('channels', 'channels.channel_id = taxonomy_trees.channel_preferences');

		$rows = ee()->db->get()->result_array();

		if(count($rows) == 1)
		{
			$row = current($rows);
			return $row['tree_id'];
		}
		else{
			return $default_tree_id;
		}
	}

}
// END CLASS

/* End of file pi.treegetter.php */