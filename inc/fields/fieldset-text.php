<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

// Make sure "text" field is loaded
require_once RWMB_FIELDS_DIR . 'text.php';

if ( ! class_exists( 'RWMB_Fieldset_Text_Field' ) )
{
	class RWMB_Fieldset_Text_Field extends RWMB_Text_Field
	{
		/**
		 * Get field HTML
		 *
		 * @param mixed $meta
		 * @param array $field
		 *
		 * @return string
		 */
		static function html( $meta, $field )
		{
			$html = array();
			$tpl  = '<label>%s %s</label>';

			for ( $row = 0; $row < $field['rows']; $row ++ )
			{
				foreach ( $field['options'] as $key => $label )
				{
					$value  = isset( $meta[$row][$key] ) ? $meta[$row][$key] : '';
					$field['attributes']['name'] = $field['field_name'] . "[{$key}]";
					$html[] = sprintf( $tpl, $label, parent::html( $value, $field) );
				}
				$html[] = '<br>';
			}

			$out = '<fieldset><legend>' . $field['desc'] . '</legend>' . implode( ' ', $html ) . '</fieldset>';

			return $out;
		}

		/**
		 * Normalize parameters for field
		 *
		 * @param array $field
		 *
		 * @return array
		 */
		static function normalize_field( $field )
		{
			$field = parent::normalize_field( $field );
			$field['multiple'] = false;
			$field['attributes']['id'] = false;
			return $field;
		}

		/**
		 * Output the field value
		 * Display options in format Label: value in unordered list
		 *
		 * @param  array    $field   Field parameters
		 * @param  array    $args    Additional arguments. Not used for these fields.
		 * @param  int|null $post_id Post ID. null for current post. Optional.
		 *
		 * @return mixed Field value
		 */
		static function the_value( $field, $args = array(), $post_id = null )
		{
			$value = self::get_value( $field, $args, $post_id );
			if ( ! $value )
				return '';

			$output = '<table>';
			$output .= '<thead><tr>';
			foreach ( $field['options'] as $label )
			{
				$output .= "<th>$label</th>";
			}
			$output .= '</tr></thead><tbody>';

			foreach ( $value as $subvalue )
			{
				$output .= '<tr>';
				foreach ( $subvalue as $value )
				{
					$output .= "<td>$value</td>";
				}
				$output .= '</tr>';
			}
			$output .= '</tbody></table>';

			return $output;
		}
	}
}
