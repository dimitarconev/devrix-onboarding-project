/**
 * WordPress components that create the necessary UI elements for the block
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-components/
 */
 import { registerBlockType } from '@wordpress/blocks';
import { TextControl } from '@wordpress/components';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { CheckboxControl } from '@wordpress/components';
import {
    useBlockProps,
    InspectorControls,
} from '@wordpress/block-editor';


const { serverSideRender: ServerSideRender } = wp;
const { Fragment } = wp.element;
/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @param {Object}   props               Properties passed to the function.
 * @param {Object}   props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function that updates individual attributes.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	if(  attributes.student  ){
		var query = {
			include: attributes.student,
		};
	} else{
		var query = {
			per_page: attributes.students_per_page,
		};
	}
	
	let posts = wp.data.select('core').getEntityRecords( 'postType', 'students', query );
	let output = "";
	if ( !posts ){
		posts = [];
	}
	posts.forEach((el)=>{
		 output = output+ "<div class='row'>"+ el.title.rendered + "</div><br>";
	})
	props => {
		return <Fragment>
			<ServerSideRender
				block={ metadata.name }
				attributes={ {
					student: attributes.student ,
					students_per_page: attributes.students_per_page,
					student_active : attributes.student_active
				} }
				/>
		</Fragment>;
	}
	const onChangeStudentsCount = ( count ) => {
		setAttributes( { student: count } );
	};

	const onChangePostsPerPage = ( posts_per_page ) => {
		setAttributes( { students_per_page: posts_per_page } );
	};
	const onChangeActive = ( active ) => {
		setAttributes( { student_active : active } );
	};
	return (
		<div { ...useBlockProps() }>
			
			<InspectorControls key="setting">
				<div id="gutenberg-controls">
					<fieldset>
						<legend className="blocks-base-control__label">
							Student
						</legend>
						<TextControl // Element Tag for Gutenberg standard colour selector
							value={ attributes.student }
							onChange={ onChangeStudentsCount } // onChange event callback
						/>
					</fieldset>
					<fieldset>
						<legend className="blocks-base-control__label">
							Posts Per page
						</legend>
						<TextControl 
							onChange={ onChangePostsPerPage }
							value={ attributes.students_per_page } 
						/>
					</fieldset>
					<fieldset>
						<legend className="blocks-base-control__label">
						</legend>
						<CheckboxControl 
							label="Active"
							checked={ attributes.student_active }
							onChange={ onChangeActive }
						/>
					</fieldset>
				</div>
			</InspectorControls>
			<div className='container'>
				  {output}
			   </div>

		</div>
		
	);
}
