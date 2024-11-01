<?php // @codingStandardsIgnoreLine.
/**
 * This file is used for service definition for drive.
 *
 * @author  Tech Banker
 * @package wp-backup-bank/lib/Google/Service
 * @version 3.0.1
 */
/**
 *
 * Copyright 2010 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

/**
 * Service definition for Drive (v2).
 *
 * <p>
 * The API to interact with Drive.</p>
 *
 * <p>
 * For more information about this service, see the API
 * <a href="https://developers.google.com/drive/" target="_blank">Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
/**
 * This class is used for google drive services
 */
class Google_Service_Drive extends Google_Service {
	/** View and manage the files in your Google Drive. */
	const DRIVE = 'https://www.googleapis.com/auth/drive';
	/** View and manage its own configuration data in your Google Drive. */
	const DRIVE_APPDATA = 'https://www.googleapis.com/auth/drive.appdata';
	/** View your Google Drive apps. */
	const DRIVE_APPS_READONLY = 'https://www.googleapis.com/auth/drive.apps.readonly';
	/** View and manage Google Drive files that you have opened or created with this app. */
	const DRIVE_FILE = 'https://www.googleapis.com/auth/drive.file';
	/** View and manage metadata of files in your Google Drive. */
	const DRIVE_METADATA = 'https://www.googleapis.com/auth/drive.metadata';
	/** View metadata for files in your Google Drive. */
	const DRIVE_METADATA_READONLY = 'https://www.googleapis.com/auth/drive.metadata.readonly';
	/** View the files in your Google Drive. */
	const DRIVE_READONLY = 'https://www.googleapis.com/auth/drive.readonly';
	/** Modify your Google Apps Script scripts' behavior. */
	const DRIVE_SCRIPTS = 'https://www.googleapis.com/auth/drive.scripts';
	/**
	 * This class is used for google services
	 *
	 * @var $about .
	 */
	public $about;
	/**
	 * This class is used for google services
	 *
	 * @var $apps .
	 */
	public $apps;
	/**
	 * This class is used for google services
	 *
	 * @var $changes .
	 */
	public $changes;
	/**
	 * This class is used for google services
	 *
	 * @var $channels .
	 */
	public $channels;
	/**
	 * This class is used for google services
	 *
	 * @var $children .
	 */
	public $children;
	/**
	 * This class is used for google services
	 *
	 * @var $comments .
	 */
	public $comments;
	/**
	 * This class is used for google services
	 *
	 * @var $files .
	 */
	public $files;
	/**
	 * This class is used for google services
	 *
	 * @var $parents .
	 */
	public $parents;
	/**
	 * This class is used for google services
	 *
	 * @var $permissions .
	 */
	public $permissions;
	/**
	 * This class is used for google services
	 *
	 * @var $properties .
	 */
	public $properties;
	/**
	 * This class is used for google services
	 *
	 * @var $realtime .
	 */
	public $realtime;
	/**
	 * This class is used for google services
	 *
	 * @var $replies .
	 */
	public $replies;
	/**
	 * This class is used for google services
	 *
	 * @var $revisions .
	 */
	public $revisions;
	/**
	 * Constructs the internal representation of the Drive service.
	 *
	 * @param Google_Client $client .
	 */
	public function __construct( Google_Client $client ) {
		parent::__construct( $client );
		$this->servicePath = 'drive/v2/';// @codingStandardsIgnoreLine.
		$this->version     = 'v2';
		$this->serviceName = 'drive';// @codingStandardsIgnoreLine.

		$this->about       = new Google_Service_Drive_About_Resource(
			$this, $this->serviceName, 'about', array(// @codingStandardsIgnoreLine.
				'methods' => array(
					'get' => array(
						'path'       => 'about',
						'httpMethod' => 'GET',
						'parameters' => array(
							'includeSubscribed' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'maxChangeIdCount'  => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'startChangeId'     => array(
								'location' => 'query',
								'type'     => 'string',
							),
						),
					),
				),
			)
		);
		$this->apps        = new Google_Service_Drive_Apps_Resource(
			$this, $this->serviceName, 'apps', array(// @codingStandardsIgnoreLine.
				'methods' => array(
					'get'  => array(
						'path'       => 'apps/{appId}',
						'httpMethod' => 'GET',
						'parameters' => array(
							'appId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'list' => array(
						'path'       => 'apps',
						'httpMethod' => 'GET',
						'parameters' => array(
							'languageCode'        => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'appFilterExtensions' => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'appFilterMimeTypes'  => array(
								'location' => 'query',
								'type'     => 'string',
							),
						),
					),
				),
			)
		);
		$this->changes     = new Google_Service_Drive_Changes_Resource(
			$this, $this->serviceName, 'changes', array(// @codingStandardsIgnoreLine.
				'methods' => array(
					'get'   => array(
						'path'       => 'changes/{changeId}',
						'httpMethod' => 'GET',
						'parameters' => array(
							'changeId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'list'  => array(
						'path'       => 'changes',
						'httpMethod' => 'GET',
						'parameters' => array(
							'includeSubscribed' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'startChangeId'     => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'includeDeleted'    => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'maxResults'        => array(
								'location' => 'query',
								'type'     => 'integer',
							),
							'pageToken'         => array(
								'location' => 'query',
								'type'     => 'string',
							),
						),
					),
					'watch' => array(
						'path'       => 'changes/watch',
						'httpMethod' => 'POST',
						'parameters' => array(
							'includeSubscribed' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'startChangeId'     => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'includeDeleted'    => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'maxResults'        => array(
								'location' => 'query',
								'type'     => 'integer',
							),
							'pageToken'         => array(
								'location' => 'query',
								'type'     => 'string',
							),
						),
					),
				),
			)
		);
		$this->channels    = new Google_Service_Drive_Channels_Resource(
			$this, $this->serviceName, 'channels', array(// @codingStandardsIgnoreLine.
				'methods' => array(
					'stop' => array(
						'path'       => 'channels/stop',
						'httpMethod' => 'POST',
						'parameters' => array(),
					),
				),
			)
		);
		$this->children    = new Google_Service_Drive_Children_Resource(
			$this, $this->serviceName, 'children', array(// @codingStandardsIgnoreLine.
				'methods' => array(
					'delete' => array(
						'path'       => 'files/{folderId}/children/{childId}',
						'httpMethod' => 'DELETE',
						'parameters' => array(
							'folderId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'childId'  => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'get'    => array(
						'path'       => 'files/{folderId}/children/{childId}',
						'httpMethod' => 'GET',
						'parameters' => array(
							'folderId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'childId'  => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'insert' => array(
						'path'       => 'files/{folderId}/children',
						'httpMethod' => 'POST',
						'parameters' => array(
							'folderId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'list'   => array(
						'path'       => 'files/{folderId}/children',
						'httpMethod' => 'GET',
						'parameters' => array(
							'folderId'   => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'q'          => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'pageToken'  => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'maxResults' => array(
								'location' => 'query',
								'type'     => 'integer',
							),
						),
					),
				),
			)
		);
		$this->comments    = new Google_Service_Drive_Comments_Resource(
			$this, $this->serviceName, 'comments', array(// @codingStandardsIgnoreLine.
				'methods' => array(
					'delete' => array(
						'path'       => 'files/{fileId}/comments/{commentId}',
						'httpMethod' => 'DELETE',
						'parameters' => array(
							'fileId'    => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'commentId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'get'    => array(
						'path'       => 'files/{fileId}/comments/{commentId}',
						'httpMethod' => 'GET',
						'parameters' => array(
							'fileId'         => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'commentId'      => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'includeDeleted' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
						),
					),
					'insert' => array(
						'path'       => 'files/{fileId}/comments',
						'httpMethod' => 'POST',
						'parameters' => array(
							'fileId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'list'   => array(
						'path'       => 'files/{fileId}/comments',
						'httpMethod' => 'GET',
						'parameters' => array(
							'fileId'         => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'pageToken'      => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'updatedMin'     => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'includeDeleted' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'maxResults'     => array(
								'location' => 'query',
								'type'     => 'integer',
							),
						),
					),
					'patch'  => array(
						'path'       => 'files/{fileId}/comments/{commentId}',
						'httpMethod' => 'PATCH',
						'parameters' => array(
							'fileId'    => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'commentId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'update' => array(
						'path'       => 'files/{fileId}/comments/{commentId}',
						'httpMethod' => 'PUT',
						'parameters' => array(
							'fileId'    => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'commentId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
				),
			)
		);
		$this->files       = new Google_Service_Drive_Files_Resource(
			$this, $this->serviceName, 'files', array(// @codingStandardsIgnoreLine.
				'methods' => array(
					'copy'       => array(
						'path'       => 'files/{fileId}/copy',
						'httpMethod' => 'POST',
						'parameters' => array(
							'fileId'             => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'convert'            => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'ocrLanguage'        => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'visibility'         => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'pinned'             => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'ocr'                => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'timedTextTrackName' => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'timedTextLanguage'  => array(
								'location' => 'query',
								'type'     => 'string',
							),
						),
					),
					'delete'     => array(
						'path'       => 'files/{fileId}',
						'httpMethod' => 'DELETE',
						'parameters' => array(
							'fileId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'emptyTrash' => array(
						'path'       => 'files/trash',
						'httpMethod' => 'DELETE',
						'parameters' => array(),
					),
					'get'        => array(
						'path'       => 'files/{fileId}',
						'httpMethod' => 'GET',
						'parameters' => array(
							'fileId'           => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'acknowledgeAbuse' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'updateViewedDate' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'revisionId'       => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'projection'       => array(
								'location' => 'query',
								'type'     => 'string',
							),
						),
					),
					'insert'     => array(
						'path'       => 'files',
						'httpMethod' => 'POST',
						'parameters' => array(
							'convert'                   => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'useContentAsIndexableText' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'ocrLanguage'               => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'visibility'                => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'pinned'                    => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'ocr'                       => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'timedTextTrackName'        => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'timedTextLanguage'         => array(
								'location' => 'query',
								'type'     => 'string',
							),
						),
					),
					'list'       => array(
						'path'       => 'files',
						'httpMethod' => 'GET',
						'parameters' => array(
							'q'          => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'pageToken'  => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'corpus'     => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'projection' => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'maxResults' => array(
								'location' => 'query',
								'type'     => 'integer',
							),
						),
					),
					'patch'      => array(
						'path'       => 'files/{fileId}',
						'httpMethod' => 'PATCH',
						'parameters' => array(
							'fileId'                    => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'addParents'                => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'updateViewedDate'          => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'removeParents'             => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'setModifiedDate'           => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'convert'                   => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'useContentAsIndexableText' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'ocrLanguage'               => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'pinned'                    => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'newRevision'               => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'ocr'                       => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'timedTextLanguage'         => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'timedTextTrackName'        => array(
								'location' => 'query',
								'type'     => 'string',
							),
						),
					),
					'touch'      => array(
						'path'       => 'files/{fileId}/touch',
						'httpMethod' => 'POST',
						'parameters' => array(
							'fileId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'trash'      => array(
						'path'       => 'files/{fileId}/trash',
						'httpMethod' => 'POST',
						'parameters' => array(
							'fileId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'untrash'    => array(
						'path'       => 'files/{fileId}/untrash',
						'httpMethod' => 'POST',
						'parameters' => array(
							'fileId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'update'     => array(
						'path'       => 'files/{fileId}',
						'httpMethod' => 'PUT',
						'parameters' => array(
							'fileId'                    => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'addParents'                => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'updateViewedDate'          => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'removeParents'             => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'setModifiedDate'           => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'convert'                   => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'useContentAsIndexableText' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'ocrLanguage'               => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'pinned'                    => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'newRevision'               => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'ocr'                       => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'timedTextLanguage'         => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'timedTextTrackName'        => array(
								'location' => 'query',
								'type'     => 'string',
							),
						),
					),
					'watch'      => array(
						'path'       => 'files/{fileId}/watch',
						'httpMethod' => 'POST',
						'parameters' => array(
							'fileId'           => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'acknowledgeAbuse' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'updateViewedDate' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'revisionId'       => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'projection'       => array(
								'location' => 'query',
								'type'     => 'string',
							),
						),
					),
				),
			)
		);
		$this->parents     = new Google_Service_Drive_Parents_Resource(
			$this, $this->serviceName, 'parents', array(// @codingStandardsIgnoreLine.
				'methods' => array(
					'delete' => array(
						'path'       => 'files/{fileId}/parents/{parentId}',
						'httpMethod' => 'DELETE',
						'parameters' => array(
							'fileId'   => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'parentId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'get'    => array(
						'path'       => 'files/{fileId}/parents/{parentId}',
						'httpMethod' => 'GET',
						'parameters' => array(
							'fileId'   => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'parentId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'insert' => array(
						'path'       => 'files/{fileId}/parents',
						'httpMethod' => 'POST',
						'parameters' => array(
							'fileId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'list'   => array(
						'path'       => 'files/{fileId}/parents',
						'httpMethod' => 'GET',
						'parameters' => array(
							'fileId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
				),
			)
		);
		$this->permissions = new Google_Service_Drive_Permissions_Resource(
			$this, $this->serviceName, 'permissions', array(// @codingStandardsIgnoreLine.
				'methods' => array(
					'delete'        => array(
						'path'       => 'files/{fileId}/permissions/{permissionId}',
						'httpMethod' => 'DELETE',
						'parameters' => array(
							'fileId'       => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'permissionId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'get'           => array(
						'path'       => 'files/{fileId}/permissions/{permissionId}',
						'httpMethod' => 'GET',
						'parameters' => array(
							'fileId'       => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'permissionId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'getIdForEmail' => array(
						'path'       => 'permissionIds/{email}',
						'httpMethod' => 'GET',
						'parameters' => array(
							'email' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'insert'        => array(
						'path'       => 'files/{fileId}/permissions',
						'httpMethod' => 'POST',
						'parameters' => array(
							'fileId'                 => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'emailMessage'           => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'sendNotificationEmails' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
						),
					),
					'list'          => array(
						'path'       => 'files/{fileId}/permissions',
						'httpMethod' => 'GET',
						'parameters' => array(
							'fileId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'patch'         => array(
						'path'       => 'files/{fileId}/permissions/{permissionId}',
						'httpMethod' => 'PATCH',
						'parameters' => array(
							'fileId'            => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'permissionId'      => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'transferOwnership' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
						),
					),
					'update'        => array(
						'path'       => 'files/{fileId}/permissions/{permissionId}',
						'httpMethod' => 'PUT',
						'parameters' => array(
							'fileId'            => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'permissionId'      => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'transferOwnership' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
						),
					),
				),
			)
		);
		$this->properties  = new Google_Service_Drive_Properties_Resource(
			$this, $this->serviceName, 'properties', array(// @codingStandardsIgnoreLine.
				'methods' => array(
					'delete' => array(
						'path'       => 'files/{fileId}/properties/{propertyKey}',
						'httpMethod' => 'DELETE',
						'parameters' => array(
							'fileId'      => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'propertyKey' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'visibility'  => array(
								'location' => 'query',
								'type'     => 'string',
							),
						),
					),
					'get'    => array(
						'path'       => 'files/{fileId}/properties/{propertyKey}',
						'httpMethod' => 'GET',
						'parameters' => array(
							'fileId'      => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'propertyKey' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'visibility'  => array(
								'location' => 'query',
								'type'     => 'string',
							),
						),
					),
					'insert' => array(
						'path'       => 'files/{fileId}/properties',
						'httpMethod' => 'POST',
						'parameters' => array(
							'fileId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'list'   => array(
						'path'       => 'files/{fileId}/properties',
						'httpMethod' => 'GET',
						'parameters' => array(
							'fileId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'patch'  => array(
						'path'       => 'files/{fileId}/properties/{propertyKey}',
						'httpMethod' => 'PATCH',
						'parameters' => array(
							'fileId'      => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'propertyKey' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'visibility'  => array(
								'location' => 'query',
								'type'     => 'string',
							),
						),
					),
					'update' => array(
						'path'       => 'files/{fileId}/properties/{propertyKey}',
						'httpMethod' => 'PUT',
						'parameters' => array(
							'fileId'      => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'propertyKey' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'visibility'  => array(
								'location' => 'query',
								'type'     => 'string',
							),
						),
					),
				),
			)
		);
		$this->realtime    = new Google_Service_Drive_Realtime_Resource(
			$this, $this->serviceName, 'realtime', array(// @codingStandardsIgnoreLine.
				'methods' => array(
					'get'    => array(
						'path'       => 'files/{fileId}/realtime',
						'httpMethod' => 'GET',
						'parameters' => array(
							'fileId'   => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'revision' => array(
								'location' => 'query',
								'type'     => 'integer',
							),
						),
					),
					'update' => array(
						'path'       => 'files/{fileId}/realtime',
						'httpMethod' => 'PUT',
						'parameters' => array(
							'fileId'       => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'baseRevision' => array(
								'location' => 'query',
								'type'     => 'string',
							),
						),
					),
				),
			)
		);
		$this->replies     = new Google_Service_Drive_Replies_Resource(
			$this, $this->serviceName, 'replies', array(// @codingStandardsIgnoreLine.
				'methods' => array(
					'delete' => array(
						'path'       => 'files/{fileId}/comments/{commentId}/replies/{replyId}',
						'httpMethod' => 'DELETE',
						'parameters' => array(
							'fileId'    => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'commentId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'replyId'   => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'get'    => array(
						'path'       => 'files/{fileId}/comments/{commentId}/replies/{replyId}',
						'httpMethod' => 'GET',
						'parameters' => array(
							'fileId'         => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'commentId'      => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'replyId'        => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'includeDeleted' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
						),
					),
					'insert' => array(
						'path'       => 'files/{fileId}/comments/{commentId}/replies',
						'httpMethod' => 'POST',
						'parameters' => array(
							'fileId'    => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'commentId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'list'   => array(
						'path'       => 'files/{fileId}/comments/{commentId}/replies',
						'httpMethod' => 'GET',
						'parameters' => array(
							'fileId'         => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'commentId'      => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'pageToken'      => array(
								'location' => 'query',
								'type'     => 'string',
							),
							'includeDeleted' => array(
								'location' => 'query',
								'type'     => 'boolean',
							),
							'maxResults'     => array(
								'location' => 'query',
								'type'     => 'integer',
							),
						),
					),
					'patch'  => array(
						'path'       => 'files/{fileId}/comments/{commentId}/replies/{replyId}',
						'httpMethod' => 'PATCH',
						'parameters' => array(
							'fileId'    => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'commentId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'replyId'   => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'update' => array(
						'path'       => 'files/{fileId}/comments/{commentId}/replies/{replyId}',
						'httpMethod' => 'PUT',
						'parameters' => array(
							'fileId'    => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'commentId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'replyId'   => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
				),
			)
		);
		$this->revisions   = new Google_Service_Drive_Revisions_Resource(
			$this, $this->serviceName, 'revisions', array(// @codingStandardsIgnoreLine.
				'methods' => array(
					'delete' => array(
						'path'       => 'files/{fileId}/revisions/{revisionId}',
						'httpMethod' => 'DELETE',
						'parameters' => array(
							'fileId'     => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'revisionId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'get'    => array(
						'path'       => 'files/{fileId}/revisions/{revisionId}',
						'httpMethod' => 'GET',
						'parameters' => array(
							'fileId'     => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'revisionId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'list'   => array(
						'path'       => 'files/{fileId}/revisions',
						'httpMethod' => 'GET',
						'parameters' => array(
							'fileId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'patch'  => array(
						'path'       => 'files/{fileId}/revisions/{revisionId}',
						'httpMethod' => 'PATCH',
						'parameters' => array(
							'fileId'     => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'revisionId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
					'update' => array(
						'path'       => 'files/{fileId}/revisions/{revisionId}',
						'httpMethod' => 'PUT',
						'parameters' => array(
							'fileId'     => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
							'revisionId' => array(
								'location' => 'path',
								'type'     => 'string',
								'required' => true,
							),
						),
					),
				),
			)
		);
	}
}
/**
 * The "about" collection of methods.
 * Typical usage is:
 *  <code>
 *   $driveService = new Google_Service_Drive(...);
 *   $about = $driveService->about;
 *  </code>
 */
class Google_Service_Drive_About_Resource extends Google_Service_Resource {// @codingStandardsIgnoreLine.
	/**
	 * Gets the information about the current user along with Drive API settings
	 * (about.get)
	 *
	 * @param array $optParams Optional parameters.
	 *
	 * @opt_param bool includeSubscribed When calculating the number of remaining
	 * change IDs, whether to include public files the user has opened and shared
	 * files. When set to false, this counts only change IDs for owned files and any
	 * shared or public files that the user has explicitly added to a folder they
	 * own.
	 * @opt_param string maxChangeIdCount Maximum number of remaining change IDs to
	 * count
	 * @opt_param string startChangeId Change ID to start counting from when
	 * calculating number of remaining change IDs
	 * @return Google_Service_Drive_About
	 */
	public function get( $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array();
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'get', array( $params ), 'Google_Service_Drive_About' );
	}
}
/**
 * The "apps" collection of methods.
 * Typical usage is:
 *  <code>
 *   $driveService = new Google_Service_Drive(...);
 *   $apps = $driveService->apps;
 *  </code>
 */
class Google_Service_Drive_Apps_Resource extends Google_Service_Resource {// @codingStandardsIgnoreLine.
	/**
	 * Gets a specific app. (apps.get)
	 *
	 * @param string $appId The ID of the app.
	 * @param array  $optParams Optional parameters.
	 * @return Google_Service_Drive_App
	 */
	public function get( $appId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'appId' => $appId );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'get', array( $params ), 'Google_Service_Drive_App' );
	}
	/**
	 * Lists a user's installed apps. (apps.listApps)
	 *
	 * @param array $optParams Optional parameters.
	 *
	 * @opt_param string languageCode A language or locale code, as defined by BCP
	 * 47, with some extensions from Unicode's LDML format
	 * (http://www.unicode.org/reports/tr35/).
	 * @opt_param string appFilterExtensions A comma-separated list of file
	 * extensions for open with filtering. All apps within the given app query scope
	 * which can open any of the given file extensions will be included in the
	 * response. If appFilterMimeTypes are provided as well, the result is a union
	 * of the two resulting app lists.
	 * @opt_param string appFilterMimeTypes A comma-separated list of MIME types for
	 * open with filtering. All apps within the given app query scope which can open
	 * any of the given MIME types will be included in the response. If
	 * appFilterExtensions are provided as well, the result is a union of the two
	 * resulting app lists.
	 * @return Google_Service_Drive_AppList
	 */

	/**
	 * This function is used for list of app.
	 *
	 * @param array $optParams .
	 */
	public function listApps( $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array();
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'list', array( $params ), 'Google_Service_Drive_AppList' );
	}
}
/**
 * The "changes" collection of methods.
 * Typical usage is:
 *  <code>
 *   $driveService = new Google_Service_Drive(...);
 *   $changes = $driveService->changes;
 *  </code>
 */
class Google_Service_Drive_Changes_Resource extends Google_Service_Resource {// @codingStandardsIgnoreLine.
	/**
	 * Gets a specific change. (changes.get)
	 *
	 * @param string $changeId The ID of the change.
	 * @param array  $optParams Optional parameters.
	 * @return Google_Service_Drive_Change
	 */
	public function get( $changeId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'changeId' => $changeId );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'get', array( $params ), 'Google_Service_Drive_Change' );
	}
	/**
	 * Lists the changes for a user. (changes.listChanges)
	 *
	 * @param array $optParams Optional parameters.
	 *
	 * @opt_param bool includeSubscribed Whether to include public files the user
	 * has opened and shared files. When set to false, the list only includes owned
	 * files plus any shared or public files the user has explicitly added to a
	 * folder they own.
	 * @opt_param string startChangeId Change ID to start listing changes from.
	 * @opt_param bool includeDeleted Whether to include deleted items.
	 * @opt_param int maxResults Maximum number of changes to return.
	 * @opt_param string pageToken Page token for changes.
	 * @return Google_Service_Drive_ChangeList
	 */
	public function listChanges( $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array();
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'list', array( $params ), 'Google_Service_Drive_ChangeList' );
	}
	/**
	 * Subscribe to changes for a user. (changes.watch)
	 *
	 * @param Google_Channel $postBody .
	 * @param array          $optParams Optional parameters.
	 *
	 * @opt_param bool includeSubscribed Whether to include public files the user
	 * has opened and shared files. When set to false, the list only includes owned
	 * files plus any shared or public files the user has explicitly added to a
	 * folder they own.
	 * @opt_param string startChangeId Change ID to start listing changes from.
	 * @opt_param bool includeDeleted Whether to include deleted items.
	 * @opt_param int maxResults Maximum number of changes to return.
	 * @opt_param string pageToken Page token for changes.
	 * @return Google_Service_Drive_Channel
	 */
	public function watch( Google_Service_Drive_Channel $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'postBody' => $postBody );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'watch', array( $params ), 'Google_Service_Drive_Channel' );
	}
}
/**
 * The "channels" collection of methods.
 * Typical usage is:
 *  <code>
 *   $driveService = new Google_Service_Drive(...);
 *   $channels = $driveService->channels;
 *  </code>
 */
class Google_Service_Drive_Channels_Resource extends Google_Service_Resource {// @codingStandardsIgnoreLine.
	/**
	 * Stop watching resources through this channel (channels.stop)
	 *
	 * @param Google_Channel $postBody .
	 * @param array          $optParams Optional parameters.
	 */
	public function stop( Google_Service_Drive_Channel $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'postBody' => $postBody );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'stop', array( $params ) );
	}
}
/**
 * The "children" collection of methods.
 * Typical usage is:
 *  <code>
 *   $driveService = new Google_Service_Drive(...);
 *   $children = $driveService->children;
 *  </code>
 */
class Google_Service_Drive_Children_Resource extends Google_Service_Resource {// @codingStandardsIgnoreLine.
	/**
	 * Removes a child from a folder. (children.delete)
	 *
	 * @param string $folderId The ID of the folder.
	 * @param string $childId The ID of the child.
	 * @param array  $optParams Optional parameters.
	 */
	public function delete( $folderId, $childId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'folderId' => $folderId,// @codingStandardsIgnoreLine.
			'childId'  => $childId,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'delete', array( $params ) );
	}
	/**
	 * Gets a specific child reference. (children.get)
	 *
	 * @param string $folderId The ID of the folder.
	 * @param string $childId The ID of the child.
	 * @param array  $optParams Optional parameters.
	 * @return Google_Service_Drive_ChildReference
	 */
	public function get( $folderId, $childId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'folderId' => $folderId,// @codingStandardsIgnoreLine.
			'childId'  => $childId,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'get', array( $params ), 'Google_Service_Drive_ChildReference' );
	}
	/**
	 * Inserts a file into a folder. (children.insert)
	 *
	 * @param string                $folderId The ID of the folder.
	 * @param Google_ChildReference $postBody .
	 * @param array                 $optParams Optional parameters.
	 * @return Google_Service_Drive_ChildReference
	 */
	public function insert( $folderId, Google_Service_Drive_ChildReference $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'folderId' => $folderId,// @codingStandardsIgnoreLine.
			'postBody' => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'insert', array( $params ), 'Google_Service_Drive_ChildReference' );
	}
	/**
	 * Lists a folder's children. (children.listChildren)
	 *
	 * @param string $folderId The ID of the folder.
	 * @param array  $optParams Optional parameters.
	 *
	 * @opt_param string q Query string for searching children.
	 * @opt_param string pageToken Page token for children.
	 * @opt_param int maxResults Maximum number of children to return.
	 * @return Google_Service_Drive_ChildList
	 */
	public function listChildren( $folderId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'folderId' => $folderId );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'list', array( $params ), 'Google_Service_Drive_ChildList' );
	}
}
/**
 * The "comments" collection of methods.
 * Typical usage is:
 *  <code>
 *   $driveService = new Google_Service_Drive(...);
 *   $comments = $driveService->comments;
 *  </code>
 */
class Google_Service_Drive_Comments_Resource extends Google_Service_Resource { // @codingStandardsIgnoreLine
	/**
	 * Deletes a comment. (comments.delete)
	 *
	 * @param string $fileId The ID of the file.
	 * @param string $commentId The ID of the comment.
	 * @param array  $optParams Optional parameters.
	 */
	public function delete( $fileId, $commentId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'    => $fileId,// @codingStandardsIgnoreLine.
			'commentId' => $commentId,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'delete', array( $params ) );
	}
	/**
	 * Gets a comment by ID. (comments.get)
	 *
	 * @param string $fileId The ID of the file.
	 * @param string $commentId The ID of the comment.
	 * @param array  $optParams Optional parameters.
	 *
	 * @opt_param bool includeDeleted If set, this will succeed when retrieving a
	 * deleted comment, and will include any deleted replies.
	 * @return Google_Service_Drive_Comment
	 */
	public function get( $fileId, $commentId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'    => $fileId,// @codingStandardsIgnoreLine.
			'commentId' => $commentId,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.// @codingStandardsIgnoreLine.
		return $this->call( 'get', array( $params ), 'Google_Service_Drive_Comment' );
	}
	/**
	 * Creates a new comment on the given file. (comments.insert)
	 *
	 * @param string         $fileId The ID of the file.
	 * @param Google_Comment $postBody .
	 * @param array          $optParams Optional parameters.
	 * @return Google_Service_Drive_Comment
	 */
	public function insert( $fileId, Google_Service_Drive_Comment $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'   => $fileId,// @codingStandardsIgnoreLine.
			'postBody' => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'insert', array( $params ), 'Google_Service_Drive_Comment' );
	}
	/**
	 * Lists a file's comments. (comments.listComments)
	 *
	 * @param string $fileId The ID of the file.
	 * @param array  $optParams Optional parameters.
	 *
	 * @opt_param string pageToken The continuation token, used to page through
	 * large result sets. To get the next page of results, set this parameter to the
	 * value of "nextPageToken" from the previous response.
	 * @opt_param string updatedMin Only discussions that were updated after this
	 * timestamp will be returned. Formatted as an RFC 3339 timestamp.
	 * @opt_param bool includeDeleted If set, all comments and replies, including
	 * deleted comments and replies (with content stripped) will be returned.
	 * @opt_param int maxResults The maximum number of discussions to include in the
	 * response, used for paging.
	 * @return Google_Service_Drive_CommentList
	 */
	public function listComments( $fileId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'fileId' => $fileId );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'list', array( $params ), 'Google_Service_Drive_CommentList' );
	}
	/**
	 * Updates an existing comment. This method supports patch semantics.
	 * (comments.patch)
	 *
	 * @param string         $fileId The ID of the file.
	 * @param string         $commentId The ID of the comment.
	 * @param Google_Comment $postBody .
	 * @param array          $optParams Optional parameters.
	 * @return Google_Service_Drive_Comment
	 */
	public function patch( $fileId, $commentId, Google_Service_Drive_Comment $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'    => $fileId,// @codingStandardsIgnoreLine.
			'commentId' => $commentId,// @codingStandardsIgnoreLine.
			'postBody'  => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'patch', array( $params ), 'Google_Service_Drive_Comment' );
	}
	/**
	 * Updates an existing comment. (comments.update)
	 *
	 * @param string         $fileId The ID of the file.
	 * @param string         $commentId The ID of the comment.
	 * @param Google_Comment $postBody .
	 * @param array          $optParams Optional parameters.
	 * @return Google_Service_Drive_Comment
	 */
	public function update( $fileId, $commentId, Google_Service_Drive_Comment $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'    => $fileId,// @codingStandardsIgnoreLine.
			'commentId' => $commentId,// @codingStandardsIgnoreLine.
			'postBody'  => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'update', array( $params ), 'Google_Service_Drive_Comment' );
	}
}
/**
 * The "files" collection of methods.
 * Typical usage is:
 *  <code>
 *   $driveService = new Google_Service_Drive(...);
 *   $files = $driveService->files;
 *  </code>
 */
class Google_Service_Drive_Files_Resource extends Google_Service_Resource {// @codingStandardsIgnoreLine.
	/**
	 * Creates a copy of the specified file. (files.copy)
	 *
	 * @param string           $fileId The ID of the file to copy.
	 * @param Google_DriveFile $postBody .
	 * @param array            $optParams Optional parameters.
	 *
	 * @opt_param bool convert Whether to convert this file to the corresponding
	 * Google Docs format.
	 * @opt_param string ocrLanguage If ocr is true, hints at the language to use.
	 * Valid values are ISO 639-1 codes.
	 * @opt_param string visibility The visibility of the new file. This parameter
	 * is only relevant when the source is not a native Google Doc and
	 * convert=false.
	 * @opt_param bool pinned Whether to pin the head revision of the new copy. A
	 * file can have a maximum of 200 pinned revisions.
	 * @opt_param bool ocr Whether to attempt OCR on .jpg, .png, .gif, or .pdf
	 * uploads.
	 * @opt_param string timedTextTrackName The timed text track name.
	 * @opt_param string timedTextLanguage The language of the timed text.
	 * @return Google_Service_Drive_DriveFile
	 */
	public function copy( $fileId, Google_Service_Drive_DriveFile $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'   => $fileId,// @codingStandardsIgnoreLine.
			'postBody' => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'copy', array( $params ), 'Google_Service_Drive_DriveFile' );
	}
	/**
	 * Permanently deletes a file by ID. Skips the trash. The currently
	 * authenticated user must own the file. (files.delete)
	 *
	 * @param string $fileId The ID of the file to delete.
	 * @param array  $optParams Optional parameters.
	 */
	public function delete( $fileId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'fileId' => $fileId );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'delete', array( $params ) );
	}
	/**
	 * Permanently deletes all of the user's trashed files. (files.emptyTrash)
	 *
	 * @param array $optParams Optional parameters.
	 */
	public function emptyTrash( $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array();
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'emptyTrash', array( $params ) );
	}
	/**
	 * Gets a file's metadata by ID. (files.get)
	 *
	 * @param string $fileId The ID for the file in question.
	 * @param array  $optParams Optional parameters.
	 *
	 * @opt_param bool acknowledgeAbuse Whether the user is acknowledging the risk
	 * of downloading known malware or other abusive files.
	 * @opt_param bool updateViewedDate Whether to update the view date after
	 * successfully retrieving the file.
	 * @opt_param string revisionId Specifies the Revision ID that should be
	 * downloaded. Ignored unless alt=media is specified.
	 * @opt_param string projection This parameter is deprecated and has no
	 * function.
	 * @return Google_Service_Drive_DriveFile
	 */
	public function get( $fileId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'fileId' => $fileId );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'get', array( $params ), 'Google_Service_Drive_DriveFile' );
	}
	/**
	 * Insert a new file. (files.insert)
	 *
	 * @param Google_DriveFile $postBody .
	 * @param array            $optParams Optional parameters.
	 *
	 * @opt_param bool convert Whether to convert this file to the corresponding
	 * Google Docs format.
	 * @opt_param bool useContentAsIndexableText Whether to use the content as
	 * indexable text.
	 * @opt_param string ocrLanguage If ocr is true, hints at the language to use.
	 * Valid values are ISO 639-1 codes.
	 * @opt_param string visibility The visibility of the new file. This parameter
	 * is only relevant when convert=false.
	 * @opt_param bool pinned Whether to pin the head revision of the uploaded file.
	 * A file can have a maximum of 200 pinned revisions.
	 * @opt_param bool ocr Whether to attempt OCR on .jpg, .png, .gif, or .pdf
	 * uploads.
	 * @opt_param string timedTextTrackName The timed text track name.
	 * @opt_param string timedTextLanguage The language of the timed text.
	 * @return Google_Service_Drive_DriveFile
	 */
	public function insert( Google_Service_Drive_DriveFile $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'postBody' => $postBody );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'insert', array( $params ), 'Google_Service_Drive_DriveFile' );
	}
	/**
	 * Lists the user's files. (files.listFiles)
	 *
	 * @param array $optParams Optional parameters.
	 *
	 * @opt_param string q Query string for searching files.
	 * @opt_param string pageToken Page token for files.
	 * @opt_param string corpus The body of items (files/documents) to which the
	 * query applies.
	 * @opt_param string projection This parameter is deprecated and has no
	 * function.
	 * @opt_param int maxResults Maximum number of files to return.
	 * @return Google_Service_Drive_FileList
	 */
	public function listFiles( $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array();
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'list', array( $params ), 'Google_Service_Drive_FileList' );
	}

	/**
	 * Updates file metadata and/or content. This method supports patch semantics.
	 * (files.patch)
	 *
	 * @param string           $fileId The ID of the file to update.
	 * @param Google_DriveFile $postBody .
	 * @param array            $optParams Optional parameters.
	 *
	 * @opt_param string addParents Comma-separated list of parent IDs to add.
	 * @opt_param bool updateViewedDate Whether to update the view date after
	 * successfully updating the file.
	 * @opt_param string removeParents Comma-separated list of parent IDs to remove.
	 * @opt_param bool setModifiedDate Whether to set the modified date with the
	 * supplied modified date.
	 * @opt_param bool convert Whether to convert this file to the corresponding
	 * Google Docs format.
	 * @opt_param bool useContentAsIndexableText Whether to use the content as
	 * indexable text.
	 * @opt_param string ocrLanguage If ocr is true, hints at the language to use.
	 * Valid values are ISO 639-1 codes.
	 * @opt_param bool pinned Whether to pin the new revision. A file can have a
	 * maximum of 200 pinned revisions.
	 * @opt_param bool newRevision Whether a blob upload should create a new
	 * revision. If false, the blob data in the current head revision is replaced.
	 * If true or not set, a new blob is created as head revision, and previous
	 * revisions are preserved (causing increased use of the user's data storage
	 * quota).
	 * @opt_param bool ocr Whether to attempt OCR on .jpg, .png, .gif, or .pdf
	 * uploads.
	 * @opt_param string timedTextLanguage The language of the timed text.
	 * @opt_param string timedTextTrackName The timed text track name.
	 * @return Google_Service_Drive_DriveFile
	 */
	public function patch( $fileId, Google_Service_Drive_DriveFile $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'   => $fileId,// @codingStandardsIgnoreLine.
			'postBody' => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'patch', array( $params ), 'Google_Service_Drive_DriveFile' );
	}
	/**
	 * Set the file's updated time to the current server time. (files.touch)
	 *
	 * @param string $fileId The ID of the file to update.
	 * @param array  $optParams Optional parameters.
	 * @return Google_Service_Drive_DriveFile
	 */
	public function touch( $fileId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'fileId' => $fileId );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'touch', array( $params ), 'Google_Service_Drive_DriveFile' );
	}
	/**
	 * Moves a file to the trash. (files.trash)
	 *
	 * @param string $fileId The ID of the file to trash.
	 * @param array  $optParams Optional parameters.
	 * @return Google_Service_Drive_DriveFile
	 */
	public function trash( $fileId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'fileId' => $fileId );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'trash', array( $params ), 'Google_Service_Drive_DriveFile' );
	}
	/**
	 * Restores a file from the trash. (files.untrash)
	 *
	 * @param string $fileId The ID of the file to untrash.
	 * @param array  $optParams Optional parameters.
	 * @return Google_Service_Drive_DriveFile
	 */
	public function untrash( $fileId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'fileId' => $fileId );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'untrash', array( $params ), 'Google_Service_Drive_DriveFile' );
	}
	/**
	 * Updates file metadata and/or content. (files.update)
	 *
	 * @param string           $fileId The ID of the file to update.
	 * @param Google_DriveFile $postBody .
	 * @param array            $optParams Optional parameters.
	 *
	 * @opt_param string addParents Comma-separated list of parent IDs to add.
	 * @opt_param bool updateViewedDate Whether to update the view date after
	 * successfully updating the file.
	 * @opt_param string removeParents Comma-separated list of parent IDs to remove.
	 * @opt_param bool setModifiedDate Whether to set the modified date with the
	 * supplied modified date.
	 * @opt_param bool convert Whether to convert this file to the corresponding
	 * Google Docs format.
	 * @opt_param bool useContentAsIndexableText Whether to use the content as
	 * indexable text.
	 * @opt_param string ocrLanguage If ocr is true, hints at the language to use.
	 * Valid values are ISO 639-1 codes.
	 * @opt_param bool pinned Whether to pin the new revision. A file can have a
	 * maximum of 200 pinned revisions.
	 * @opt_param bool newRevision Whether a blob upload should create a new
	 * revision. If false, the blob data in the current head revision is replaced.
	 * If true or not set, a new blob is created as head revision, and previous
	 * revisions are preserved (causing increased use of the user's data storage
	 * quota).
	 * @opt_param bool ocr Whether to attempt OCR on .jpg, .png, .gif, or .pdf
	 * uploads.
	 * @opt_param string timedTextLanguage The language of the timed text.
	 * @opt_param string timedTextTrackName The timed text track name.
	 * @return Google_Service_Drive_DriveFile
	 */
	public function update( $fileId, Google_Service_Drive_DriveFile $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'   => $fileId,// @codingStandardsIgnoreLine.
			'postBody' => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'update', array( $params ), 'Google_Service_Drive_DriveFile' );
	}
	/**
	 * Subscribe to changes on a file (files.watch)
	 *
	 * @param string         $fileId The ID for the file in question.
	 * @param Google_Channel $postBody .
	 * @param array          $optParams Optional parameters.
	 *
	 * @opt_param bool acknowledgeAbuse Whether the user is acknowledging the risk
	 * of downloading known malware or other abusive files.
	 * @opt_param bool updateViewedDate Whether to update the view date after
	 * successfully retrieving the file.
	 * @opt_param string revisionId Specifies the Revision ID that should be
	 * downloaded. Ignored unless alt=media is specified.
	 * @opt_param string projection This parameter is deprecated and has no
	 * function.
	 * @return Google_Service_Drive_Channel
	 */
	public function watch( $fileId, Google_Service_Drive_Channel $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'   => $fileId,// @codingStandardsIgnoreLine.
			'postBody' => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'watch', array( $params ), 'Google_Service_Drive_Channel' );
	}
}
/**
 * The "parents" collection of methods.
 * Typical usage is:
 *  <code>
 *   $driveService = new Google_Service_Drive(...);
 *   $parents = $driveService->parents;
 *  </code>
 */
class Google_Service_Drive_Parents_Resource extends Google_Service_Resource {// @codingStandardsIgnoreLine.
	/**
	 * Removes a parent from a file. (parents.delete)
	 *
	 * @param string $fileId The ID of the file.
	 * @param string $parentId The ID of the parent.
	 * @param array  $optParams Optional parameters.
	 */
	public function delete( $fileId, $parentId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'   => $fileId,// @codingStandardsIgnoreLine.
			'parentId' => $parentId,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'delete', array( $params ) );
	}
	/**
	 * Gets a specific parent reference. (parents.get)
	 *
	 * @param string $fileId The ID of the file.
	 * @param string $parentId The ID of the parent.
	 * @param array  $optParams Optional parameters.
	 * @return Google_Service_Drive_ParentReference
	 */
	public function get( $fileId, $parentId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'   => $fileId,// @codingStandardsIgnoreLine.
			'parentId' => $parentId,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'get', array( $params ), 'Google_Service_Drive_ParentReference' );
	}
	/**
	 * Adds a parent folder for a file. (parents.insert)
	 *
	 * @param string                 $fileId The ID of the file.
	 * @param Google_ParentReference $postBody .
	 * @param array                  $optParams Optional parameters.
	 * @return Google_Service_Drive_ParentReference
	 */
	public function insert( $fileId, Google_Service_Drive_ParentReference $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'   => $fileId,// @codingStandardsIgnoreLine.
			'postBody' => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'insert', array( $params ), 'Google_Service_Drive_ParentReference' );
	}
	/**
	 * Lists a file's parents. (parents.listParents)
	 *
	 * @param string $fileId The ID of the file.
	 * @param array  $optParams Optional parameters.
	 * @return Google_Service_Drive_ParentList
	 */
	public function listParents( $fileId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'fileId' => $fileId );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'list', array( $params ), 'Google_Service_Drive_ParentList' );
	}
}
/**
 * The "permissions" collection of methods.
 * Typical usage is:
 *  <code>
 *   $driveService = new Google_Service_Drive(...);
 *   $permissions = $driveService->permissions;
 *  </code>
 */
class Google_Service_Drive_Permissions_Resource extends Google_Service_Resource {// @codingStandardsIgnoreLine.
	/**
	 * Deletes a permission from a file. (permissions.delete)
	 *
	 * @param string $fileId The ID for the file.
	 * @param string $permissionId The ID for the permission.
	 * @param array  $optParams Optional parameters.
	 */
	public function delete( $fileId, $permissionId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'       => $fileId,// @codingStandardsIgnoreLine.
			'permissionId' => $permissionId,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'delete', array( $params ) );
	}
	/**
	 * Gets a permission by ID. (permissions.get)
	 *
	 * @param string $fileId The ID for the file.
	 * @param string $permissionId The ID for the permission.
	 * @param array  $optParams Optional parameters.
	 * @return Google_Service_Drive_Permission
	 */
	public function get( $fileId, $permissionId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'       => $fileId,// @codingStandardsIgnoreLine.
			'permissionId' => $permissionId,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'get', array( $params ), 'Google_Service_Drive_Permission' );
	}
	/**
	 * Returns the permission ID for an email address. (permissions.getIdForEmail)
	 *
	 * @param string $email The email address for which to return a permission ID .
	 * @param array  $optParams Optional parameters.
	 * @return Google_Service_Drive_PermissionId
	 */
	public function getIdForEmail( $email, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'email' => $email );
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'getIdForEmail', array( $params ), 'Google_Service_Drive_PermissionId' );
	}
	/**
	 * Inserts a permission for a file. (permissions.insert)
	 *
	 * @param string            $fileId The ID for the file.
	 * @param Google_Permission $postBody .
	 * @param array             $optParams Optional parameters.
	 *
	 * @opt_param string emailMessage A custom message to include in notification
	 * emails.
	 * @opt_param bool sendNotificationEmails Whether to send notification emails
	 * when sharing to users or groups. This parameter is ignored and an email is
	 * sent if the role is owner.
	 * @return Google_Service_Drive_Permission
	 */
	public function insert( $fileId, Google_Service_Drive_Permission $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'   => $fileId,// @codingStandardsIgnoreLine.
			'postBody' => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'insert', array( $params ), 'Google_Service_Drive_Permission' );
	}
	/**
	 * Lists a file's permissions. (permissions.listPermissions)
	 *
	 * @param string $fileId The ID for the file.
	 * @param array  $optParams Optional parameters.
	 * @return Google_Service_Drive_PermissionList
	 */
	public function listPermissions( $fileId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'fileId' => $fileId );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'list', array( $params ), 'Google_Service_Drive_PermissionList' );
	}
	/**
	 * Updates a permission. This method supports patch semantics.
	 * (permissions.patch)
	 *
	 * @param string            $fileId The ID for the file.
	 * @param string            $permissionId The ID for the permission.
	 * @param Google_Permission $postBody .
	 * @param array             $optParams Optional parameters.
	 *
	 * @opt_param bool transferOwnership Whether changing a role to 'owner'
	 * downgrades the current owners to writers. Does nothing if the specified role
	 * is not 'owner'.
	 * @return Google_Service_Drive_Permission
	 */
	public function patch( $fileId, $permissionId, Google_Service_Drive_Permission $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'       => $fileId,// @codingStandardsIgnoreLine.
			'permissionId' => $permissionId,// @codingStandardsIgnoreLine.
			'postBody'     => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'patch', array( $params ), 'Google_Service_Drive_Permission' );
	}
	/**
	 * Updates a permission. (permissions.update)
	 *
	 * @param string            $fileId The ID for the file.
	 * @param string            $permissionId The ID for the permission.
	 * @param Google_Permission $postBody .
	 * @param array             $optParams Optional parameters.
	 *
	 * @opt_param bool transferOwnership Whether changing a role to 'owner'
	 * downgrades the current owners to writers. Does nothing if the specified role
	 * is not 'owner'.
	 * @return Google_Service_Drive_Permission
	 */
	public function update( $fileId, $permissionId, Google_Service_Drive_Permission $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'       => $fileId,// @codingStandardsIgnoreLine.
			'permissionId' => $permissionId,// @codingStandardsIgnoreLine.
			'postBody'     => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'update', array( $params ), 'Google_Service_Drive_Permission' );
	}
}
/**
 * The "properties" collection of methods.
 * Typical usage is:
 *  <code>
 *   $driveService = new Google_Service_Drive(...);
 *   $properties = $driveService->properties;
 *  </code>
 */
class Google_Service_Drive_Properties_Resource extends Google_Service_Resource {// @codingStandardsIgnoreLine.
	/**
	 * Deletes a property. (properties.delete)
	 *
	 * @param string $fileId The ID of the file.
	 * @param string $propertyKey The key of the property.
	 * @param array  $optParams Optional parameters.
	 *
	 * @opt_param string visibility The visibility of the property.
	 */
	public function delete( $fileId, $propertyKey, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'      => $fileId,// @codingStandardsIgnoreLine.
			'propertyKey' => $propertyKey,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'delete', array( $params ) );
	}
	/**
	 * Gets a property by its key. (properties.get)
	 *
	 * @param string $fileId The ID of the file.
	 * @param string $propertyKey The key of the property.
	 * @param array  $optParams Optional parameters.
	 *
	 * @opt_param string visibility The visibility of the property.
	 * @return Google_Service_Drive_Property
	 */
	public function get( $fileId, $propertyKey, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'      => $fileId,// @codingStandardsIgnoreLine.
			'propertyKey' => $propertyKey,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'get', array( $params ), 'Google_Service_Drive_Property' );
	}
	/**
	 * Adds a property to a file. (properties.insert)
	 *
	 * @param string          $fileId The ID of the file.
	 * @param Google_Property $postBody .
	 * @param array           $optParams Optional parameters.
	 * @return Google_Service_Drive_Property
	 */
	public function insert( $fileId, Google_Service_Drive_Property $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'   => $fileId,// @codingStandardsIgnoreLine.
			'postBody' => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'insert', array( $params ), 'Google_Service_Drive_Property' );
	}
	/**
	 * Lists a file's properties. (properties.listProperties)
	 *
	 * @param string $fileId The ID of the file.
	 * @param array  $optParams Optional parameters.
	 * @return Google_Service_Drive_PropertyList
	 */
	public function listProperties( $fileId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'fileId' => $fileId );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'list', array( $params ), 'Google_Service_Drive_PropertyList' );
	}
	/**
	 * Updates a property. This method supports patch semantics. (properties.patch)
	 *
	 * @param string          $fileId The ID of the file.
	 * @param string          $propertyKey The key of the property.
	 * @param Google_Property $postBody .
	 * @param array           $optParams Optional parameters.
	 *
	 * @opt_param string visibility The visibility of the property.
	 * @return Google_Service_Drive_Property
	 */
	public function patch( $fileId, $propertyKey, Google_Service_Drive_Property $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'      => $fileId,// @codingStandardsIgnoreLine.
			'propertyKey' => $propertyKey,// @codingStandardsIgnoreLine.
			'postBody'    => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'patch', array( $params ), 'Google_Service_Drive_Property' );
	}
	/**
	 * Updates a property. (properties.update)
	 *
	 * @param string          $fileId The ID of the file.
	 * @param string          $propertyKey The key of the property.
	 * @param Google_Property $postBody .
	 * @param array           $optParams Optional parameters.
	 *
	 * @opt_param string visibility The visibility of the property.
	 * @return Google_Service_Drive_Property
	 */
	public function update( $fileId, $propertyKey, Google_Service_Drive_Property $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'      => $fileId,// @codingStandardsIgnoreLine.
			'propertyKey' => $propertyKey,// @codingStandardsIgnoreLine.
			'postBody'    => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'update', array( $params ), 'Google_Service_Drive_Property' );
	}
}
/**
 * The "realtime" collection of methods.
 * Typical usage is:
 *  <code>
 *   $driveService = new Google_Service_Drive(...);
 *   $realtime = $driveService->realtime;
 *  </code>
 */
class Google_Service_Drive_Realtime_Resource extends Google_Service_Resource {// @codingStandardsIgnoreLine.
	/**
	 * Exports the contents of the Realtime API data model associated with this file
	 * as JSON. (realtime.get)
	 *
	 * @param string $fileId The ID of the file that the Realtime API data model is
	 * associated with.
	 * @param array  $optParams Optional parameters.
	 *
	 * @opt_param int revision The revision of the Realtime API data model to
	 * export. Revisions start at 1 (the initial empty data model) and are
	 * incremented with each change. If this parameter is excluded, the most recent
	 * data model will be returned.
	 */
	public function get( $fileId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'fileId' => $fileId );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'get', array( $params ) );
	}
	/**
	 * Overwrites the Realtime API data model associated with this file with the
	 * provided JSON data model. (realtime.update)
	 *
	 * @param string $fileId The ID of the file that the Realtime API data model is
	 * associated with.
	 * @param array  $optParams Optional parameters.
	 *
	 * @opt_param string baseRevision The revision of the model to diff the uploaded
	 * model against. If set, the uploaded model is diffed against the provided
	 * revision and those differences are merged with any changes made to the model
	 * after the provided revision. If not set, the uploaded model replaces the
	 * current model on the server.
	 */
	public function update( $fileId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'fileId' => $fileId );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'update', array( $params ) );
	}
}
/**
 * The "replies" collection of methods.
 * Typical usage is:
 *  <code>
 *   $driveService = new Google_Service_Drive(...);
 *   $replies = $driveService->replies;
 *  </code>
 */
class Google_Service_Drive_Replies_Resource extends Google_Service_Resource {// @codingStandardsIgnoreLine.
	/**
	 * Deletes a reply. (replies.delete)
	 *
	 * @param string $fileId The ID of the file.
	 * @param string $commentId The ID of the comment.
	 * @param string $replyId The ID of the reply.
	 * @param array  $optParams Optional parameters.
	 */
	public function delete( $fileId, $commentId, $replyId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'    => $fileId,// @codingStandardsIgnoreLine.
			'commentId' => $commentId,// @codingStandardsIgnoreLine.
			'replyId'   => $replyId,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'delete', array( $params ) );
	}
	/**
	 * Gets a reply. (replies.get)
	 *
	 * @param string $fileId The ID of the file.
	 * @param string $commentId The ID of the comment.
	 * @param string $replyId The ID of the reply.
	 * @param array  $optParams Optional parameters.
	 *
	 * @opt_param bool includeDeleted If set, this will succeed when retrieving a
	 * deleted reply.
	 * @return Google_Service_Drive_CommentReply
	 */
	public function get( $fileId, $commentId, $replyId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'    => $fileId,// @codingStandardsIgnoreLine.
			'commentId' => $commentId,// @codingStandardsIgnoreLine.
			'replyId'   => $replyId,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'get', array( $params ), 'Google_Service_Drive_CommentReply' );
	}
	/**
	 * Creates a new reply to the given comment. (replies.insert)
	 *
	 * @param string              $fileId The ID of the file.
	 * @param string              $commentId The ID of the comment.
	 * @param Google_CommentReply $postBody .
	 * @param array               $optParams Optional parameters.
	 * @return Google_Service_Drive_CommentReply
	 */
	public function insert( $fileId, $commentId, Google_Service_Drive_CommentReply $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'    => $fileId,// @codingStandardsIgnoreLine.
			'commentId' => $commentId,// @codingStandardsIgnoreLine.
			'postBody'  => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'insert', array( $params ), 'Google_Service_Drive_CommentReply' );
	}
	/**
	 * Lists all of the replies to a comment. (replies.listReplies)
	 *
	 * @param string $fileId The ID of the file.
	 * @param string $commentId The ID of the comment.
	 * @param array  $optParams Optional parameters.
	 *
	 * @opt_param string pageToken The continuation token, used to page through
	 * large result sets. To get the next page of results, set this parameter to the
	 * value of "nextPageToken" from the previous response.
	 * @opt_param bool includeDeleted If set, all replies, including deleted replies
	 * (with content stripped) will be returned.
	 * @opt_param int maxResults The maximum number of replies to include in the
	 * response, used for paging.
	 * @return Google_Service_Drive_CommentReplyList
	 */
	public function listReplies( $fileId, $commentId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'    => $fileId,// @codingStandardsIgnoreLine.
			'commentId' => $commentId,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'list', array( $params ), 'Google_Service_Drive_CommentReplyList' );
	}
	/**
	 * Updates an existing reply. This method supports patch semantics.
	 * (replies.patch)
	 *
	 * @param string              $fileId The ID of the file.
	 * @param string              $commentId The ID of the comment.
	 * @param string              $replyId The ID of the reply.
	 * @param Google_CommentReply $postBody .
	 * @param array               $optParams Optional parameters.
	 * @return Google_Service_Drive_CommentReply
	 */
	public function patch( $fileId, $commentId, $replyId, Google_Service_Drive_CommentReply $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'    => $fileId,// @codingStandardsIgnoreLine.
			'commentId' => $commentId,// @codingStandardsIgnoreLine.
			'replyId'   => $replyId,// @codingStandardsIgnoreLine.
			'postBody'  => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'patch', array( $params ), 'Google_Service_Drive_CommentReply' );
	}
	/**
	 * Updates an existing reply. (replies.update)
	 *
	 * @param string              $fileId The ID of the file.
	 * @param string              $commentId The ID of the comment.
	 * @param string              $replyId The ID of the reply.
	 * @param Google_CommentReply $postBody .
	 * @param array               $optParams Optional parameters.
	 * @return Google_Service_Drive_CommentReply
	 */
	public function update( $fileId, $commentId, $replyId, Google_Service_Drive_CommentReply $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'    => $fileId,// @codingStandardsIgnoreLine.
			'commentId' => $commentId,// @codingStandardsIgnoreLine.
			'replyId'   => $replyId,// @codingStandardsIgnoreLine.
			'postBody'  => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'update', array( $params ), 'Google_Service_Drive_CommentReply' );
	}
}
/**
 * The "revisions" collection of methods.
 * Typical usage is:
 *  <code>
 *   $driveService = new Google_Service_Drive(...);
 *   $revisions = $driveService->revisions;
 *  </code>
 */
class Google_Service_Drive_Revisions_Resource extends Google_Service_Resource {// @codingStandardsIgnoreLine.
	/**
	 * Removes a revision. (revisions.delete)
	 *
	 * @param string $fileId The ID of the file.
	 * @param string $revisionId The ID of the revision.
	 * @param array  $optParams Optional parameters.
	 */
	public function delete( $fileId, $revisionId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'     => $fileId,// @codingStandardsIgnoreLine.
			'revisionId' => $revisionId,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'delete', array( $params ) );
	}
	/**
	 * Gets a specific revision. (revisions.get)
	 *
	 * @param string $fileId The ID of the file.
	 * @param string $revisionId The ID of the revision.
	 * @param array  $optParams Optional parameters.
	 * @return Google_Service_Drive_Revision
	 */
	public function get( $fileId, $revisionId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'     => $fileId,// @codingStandardsIgnoreLine.
			'revisionId' => $revisionId,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'get', array( $params ), 'Google_Service_Drive_Revision' );
	}
	/**
	 * Lists a file's revisions. (revisions.listRevisions)
	 *
	 * @param string $fileId The ID of the file.
	 * @param array  $optParams Optional parameters.
	 * @return Google_Service_Drive_RevisionList
	 */
	public function listRevisions( $fileId, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array( 'fileId' => $fileId );// @codingStandardsIgnoreLine.
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'list', array( $params ), 'Google_Service_Drive_RevisionList' );
	}
	/**
	 * Updates a revision. This method supports patch semantics. (revisions.patch)
	 *
	 * @param string          $fileId The ID for the file.
	 * @param string          $revisionId The ID for the revision.
	 * @param Google_Revision $postBody .
	 * @param array           $optParams Optional parameters.
	 * @return Google_Service_Drive_Revision
	 */
	public function patch( $fileId, $revisionId, Google_Service_Drive_Revision $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'     => $fileId,// @codingStandardsIgnoreLine.
			'revisionId' => $revisionId,// @codingStandardsIgnoreLine.
			'postBody'   => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'patch', array( $params ), 'Google_Service_Drive_Revision' );
	}
	/**
	 * Updates a revision. (revisions.update)
	 *
	 * @param string          $fileId The ID for the file.
	 * @param string          $revisionId The ID for the revision.
	 * @param Google_Revision $postBody .
	 * @param array           $optParams Optional parameters.
	 * @return Google_Service_Drive_Revision
	 */
	public function update( $fileId, $revisionId, Google_Service_Drive_Revision $postBody, $optParams = array() ) {// @codingStandardsIgnoreLine.
		$params = array(
			'fileId'     => $fileId,// @codingStandardsIgnoreLine.
			'revisionId' => $revisionId,// @codingStandardsIgnoreLine.
			'postBody'   => $postBody,// @codingStandardsIgnoreLine.
		);
		$params = array_merge( $params, $optParams );// @codingStandardsIgnoreLine.
		return $this->call( 'update', array( $params ), 'Google_Service_Drive_Revision' );
	}
}
/**
 * This class is used for google services
 */
class Google_Service_Drive_About extends Google_Collection {// @codingStandardsIgnoreLine.
	/**
	 * Collection of key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'quotaBytesByService';
	/**
	 * Internal google api mappings .
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Additional role info type
	 *
	 * @var $additionalRoleInfoType .
	 */
	protected $additionalRoleInfoType = 'Google_Service_Drive_AboutAdditionalRoleInfo';// @codingStandardsIgnoreLine.
	/**
	 * Additional role info data type
	 *
	 * @var $additionalRoleInfoDataType .
	 */
	protected $additionalRoleInfoDataType = 'array';// @codingStandardsIgnoreLine.
	/**
	 * Policy to share domain
	 *
	 * @var $domainSharingPolicy .
	 */
	public $domainSharingPolicy;// @codingStandardsIgnoreLine.
	/**
	 * For variable etag
	 *
	 * @var $etag .
	 */
	public $etag;
	/**
	 * Export format type
	 *
	 * @var $exportFormatsType .
	 */
	protected $exportFormatsType = 'Google_Service_Drive_AboutExportFormats';// @codingStandardsIgnoreLine.
	/**
	 * Export format data type
	 *
	 * @var $exportFormatsDataType .
	 */
	protected $exportFormatsDataType = 'array';// @codingStandardsIgnoreLine.
	/**
	 * Features type
	 *
	 * @var $featuresType .
	 */
	protected $featuresType = 'Google_Service_Drive_AboutFeatures';// @codingStandardsIgnoreLine.
	/**
	 * Features data type
	 *
	 * @var $featuresDataType .
	 */
	protected $featuresDataType = 'array';// @codingStandardsIgnoreLine.
	/**
	 * Folder color palete
	 *
	 * @var $folderColorPalette .
	 */
	public $folderColorPalette;// @codingStandardsIgnoreLine.
	/**
	 * Import format type
	 *
	 * @var $importFormatsType .
	 */
	protected $importFormatsType = 'Google_Service_Drive_AboutImportFormats';// @codingStandardsIgnoreLine.
	/**
	 * Import format data type
	 *
	 * @var $importFormatsDataType .
	 */
	protected $importFormatsDataType = 'array';// @codingStandardsIgnoreLine.
	/**
	 * Current app install
	 *
	 * @var $isCurrentAppInstalled .
	 */
	public $isCurrentAppInstalled;// @codingStandardsIgnoreLine.
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Language code
	 *
	 * @var $languageCode .
	 */
	public $languageCode;// @codingStandardsIgnoreLine.
	/**
	 * Largest change id variable with access of public
	 *
	 * @var $largestChangeId .
	 */
	public $largestChangeId;// @codingStandardsIgnoreLine.
	/**
	 * Max upload size type
	 *
	 * @var $maxUploadSizesType .
	 */
	protected $maxUploadSizesType = 'Google_Service_Drive_AboutMaxUploadSizes';// @codingStandardsIgnoreLine.
	/**
	 * Max upload size data type
	 *
	 * @var $maxUploadSizesDataType .
	 */
	protected $maxUploadSizesDataType = 'array';// @codingStandardsIgnoreLine.
	/**
	 * Nmae variable with access public .
	 *
	 * @var $name .
	 */
	public $name;
	/**
	 * Premission id with public access
	 *
	 * @var $permissionId .
	 */
	public $permissionId;// @codingStandardsIgnoreLine.
	/**
	 * Service type by quota byte
	 *
	 * @var $quotaBytesByServiceType .
	 */
	protected $quotaBytesByServiceType = 'Google_Service_Drive_AboutQuotaBytesByService';// @codingStandardsIgnoreLine.
	/**
	 * Service data type by quota byte
	 *
	 * @var $quotaBytesByServiceDataType .
	 */
	protected $quotaBytesByServiceDataType = 'array';// @codingStandardsIgnoreLine.
	/**
	 * Total quota byte with public access
	 *
	 * @var $quotaBytesTotal .
	 */
	public $quotaBytesTotal;// @codingStandardsIgnoreLine.
	/**
	 * Quota byte used with public access
	 *
	 * @var $quotaBytesUsed .
	 */
	public $quotaBytesUsed;// @codingStandardsIgnoreLine.
	/**
	 * Quota byte tha use aggregate
	 *
	 * @var $quotaBytesUsedAggregate .
	 */
	public $quotaBytesUsedAggregate;// @codingStandardsIgnoreLine.
	/**
	 * Quota byte used in trash
	 *
	 * @var $quotaBytesUsedInTrash .
	 */
	public $quotaBytesUsedInTrash;// @codingStandardsIgnoreLine.
	/**
	 * Type of quota
	 *
	 * @var $quotaType .
	 */
	public $quotaType;// @codingStandardsIgnoreLine.
	/**
	 * Change id remains
	 *
	 * @var $remainingChangeIds .
	 */
	public $remainingChangeIds;// @codingStandardsIgnoreLine.
	/**
	 * Folder id of root
	 *
	 * @var $rootFolderId .
	 */
	public $rootFolderId;// @codingStandardsIgnoreLine.
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink;// @codingStandardsIgnoreLine.
	/**
	 * User types
	 *
	 * @var $userType .
	 */
	protected $userType = 'Google_Service_Drive_User';// @codingStandardsIgnoreLine.
	/**
	 * User data type
	 *
	 * @var $userDataType .
	 */
	protected $userDataType = '';// @codingStandardsIgnoreLine.
	/**
	 * This function is used to set additional role info
	 *
	 * @param string $additionalRoleInfo .
	 */
	public function setAdditionalRoleInfo( $additionalRoleInfo ) {// @codingStandardsIgnoreLine.
		$this->additionalRoleInfo = $additionalRoleInfo;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get additional role info
	 */
	public function getAdditionalRoleInfo() {// @codingStandardsIgnoreLine.
		return $this->additionalRoleInfo;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set sharing the domain
	 *
	 * @param string $domainSharingPolicy .
	 */
	public function setDomainSharingPolicy( $domainSharingPolicy ) {// @codingStandardsIgnoreLine.
		$this->domainSharingPolicy = $domainSharingPolicy;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get sharing the domain
	 */
	public function getDomainSharingPolicy() {// @codingStandardsIgnoreLine.
		return $this->domainSharingPolicy;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set etag
	 *
	 * @param string $etag .
	 */
	public function setEtag( $etag ) {// @codingStandardsIgnoreLine.
		$this->etag = $etag;
	}
	/**
	 * This function is used to get etag
	 */
	public function getEtag() {// @codingStandardsIgnoreLine.
		return $this->etag;
	}
	/**
	 * This function is used to set export formats
	 *
	 * @param string $exportFormats .
	 */
	public function setExportFormats( $exportFormats ) {// @codingStandardsIgnoreLine.
		$this->exportFormats = $exportFormats;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get export formats
	 */
	public function getExportFormats() {// @codingStandardsIgnoreLine.
		return $this->exportFormats;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set features
	 *
	 * @param string $features .
	 */
	public function setFeatures( $features ) {// @codingStandardsIgnoreLine.
		$this->features = $features;
	}
	/**
	 * This function is used to get features
	 */
	public function getFeatures() {// @codingStandardsIgnoreLine.
		return $this->features;
	}
	/**
	 * This function is used to set color folder palatte
	 *
	 * @param string $folderColorPalette .
	 */
	public function setFolderColorPalette( $folderColorPalette ) {// @codingStandardsIgnoreLine.
		$this->folderColorPalette = $folderColorPalette;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get color folder palatte
	 */
	public function getFolderColorPalette() {// @codingStandardsIgnoreLine.
		return $this->folderColorPalette;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set import formats
	 *
	 * @param string $importFormats .
	 */
	public function setImportFormats( $importFormats ) {// @codingStandardsIgnoreLine.
		$this->importFormats = $importFormats;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get import formats
	 */
	public function getImportFormats() {// @codingStandardsIgnoreLine.
		return $this->importFormats;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set current app instal
	 *
	 * @param string $isCurrentAppInstalled .
	 */
	public function setIsCurrentAppInstalled( $isCurrentAppInstalled ) {// @codingStandardsIgnoreLine.
		$this->isCurrentAppInstalled = $isCurrentAppInstalled;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set current app instal
	 */
	public function getIsCurrentAppInstalled() {// @codingStandardsIgnoreLine.
		return $this->isCurrentAppInstalled;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set language code
	 *
	 * @param string $languageCode .
	 */
	public function setLanguageCode( $languageCode ) {// @codingStandardsIgnoreLine.
		$this->languageCode = $languageCode;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get language code
	 */
	public function getLanguageCode() {
		return $this->languageCode;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set largest change id
	 *
	 * @param string $largestChangeId .
	 */
	public function setLargestChangeId( $largestChangeId ) {// @codingStandardsIgnoreLine.
		$this->largestChangeId = $largestChangeId;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get largest change id
	 */
	public function getLargestChangeId() {
		return $this->largestChangeId;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set max upload size
	 *
	 * @param string $maxUploadSizes .
	 */
	public function setMaxUploadSizes( $maxUploadSizes ) {// @codingStandardsIgnoreLine.
		$this->maxUploadSizes = $maxUploadSizes;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get max upload size
	 */
	public function getMaxUploadSizes() {
		return $this->maxUploadSizes;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set name
	 *
	 * @param string $name .
	 */
	public function setName( $name ) {
		$this->name = $name;
	}
	/**
	 * This function is used to get name
	 */
	public function getName() {
		return $this->name;
	}
	/**
	 * This function is used to set permission id
	 *
	 * @param string $permissionId .
	 */
	public function setPermissionId( $permissionId ) {// @codingStandardsIgnoreLine.
		$this->permissionId = $permissionId;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get premission id
	 */
	public function getPermissionId() {
		return $this->permissionId;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set quota byte by service
	 *
	 * @param string $quotaBytesByService .
	 */
	public function setQuotaBytesByService( $quotaBytesByService ) {// @codingStandardsIgnoreLine.
		$this->quotaBytesByService = $quotaBytesByService;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get quota byte by service
	 */
	public function getQuotaBytesByService() {
		return $this->quotaBytesByService;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set total quota byte
	 *
	 * @param string $quotaBytesTotal .
	 */
	public function setQuotaBytesTotal( $quotaBytesTotal ) {// @codingStandardsIgnoreLine.
		$this->quotaBytesTotal = $quotaBytesTotal;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get total quota byte
	 */
	public function getQuotaBytesTotal() {
		return $this->quotaBytesTotal;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set quota byte used
	 *
	 * @param string $quotaBytesUsed .
	 */
	public function setQuotaBytesUsed( $quotaBytesUsed ) {// @codingStandardsIgnoreLine.
		$this->quotaBytesUsed = $quotaBytesUsed;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to  get quota byte used
	 */
	public function getQuotaBytesUsed() {
		return $this->quotaBytesUsed;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set quota byte used aggregate
	 *
	 * @param string $quotaBytesUsedAggregate .
	 */
	public function setQuotaBytesUsedAggregate( $quotaBytesUsedAggregate ) {// @codingStandardsIgnoreLine.
		$this->quotaBytesUsedAggregate = $quotaBytesUsedAggregate;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get quota byte used aggregate
	 */
	public function getQuotaBytesUsedAggregate() {
		return $this->quotaBytesUsedAggregate;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get quota byte used in trash
	 *
	 * @param string $quotaBytesUsedInTrash .
	 */
	public function setQuotaBytesUsedInTrash( $quotaBytesUsedInTrash ) {// @codingStandardsIgnoreLine.
		$this->quotaBytesUsedInTrash = $quotaBytesUsedInTrash;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set quota byte used in trash
	 */
	public function getQuotaBytesUsedInTrash() {
		return $this->quotaBytesUsedInTrash;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set quota type
	 *
	 * @param string $quotaType .
	 */
	public function setQuotaType( $quotaType ) {// @codingStandardsIgnoreLine.
		$this->quotaType = $quotaType;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get quota type
	 */
	public function getQuotaType() {
		return $this->quotaType;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set remaining change id
	 *
	 * @param string $remainingChangeIds .
	 */
	public function setRemainingChangeIds( $remainingChangeIds ) {// @codingStandardsIgnoreLine.
		$this->remainingChangeIds = $remainingChangeIds;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get remaining change id
	 */
	public function getRemainingChangeIds() {
		return $this->remainingChangeIds;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set root folder id
	 *
	 * @param string $rootFolderId .
	 */
	public function setRootFolderId( $rootFolderId ) {// @codingStandardsIgnoreLine.
		$this->rootFolderId = $rootFolderId;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get root folder id
	 */
	public function getRootFolderId() {
		return $this->rootFolderId;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set user
	 *
	 * @param Google_Service_Drive_User $user .
	 */
	public function setUser( Google_Service_Drive_User $user ) {// @codingStandardsIgnoreLine.
		$this->user = $user;
	}
	/**
	 * This function is used to get user
	 */
	public function getUser() {
		return $this->user;
	}
}
/**
 * This class is for additional role info
 */
class Google_Service_Drive_AboutAdditionalRoleInfo extends Google_Collection {// @codingStandardsIgnoreLine.
	/**
	 * For collection key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'roleSets';
	/**
	 * For internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * For role set type
	 *
	 * @var $roleSetsType .
	 */
	protected $roleSetsType = 'Google_Service_Drive_AboutAdditionalRoleInfoRoleSets';// @codingStandardsIgnoreLine.
	/**
	 * For role set data type
	 *
	 * @var $roleSetsDataType .
	 */
	protected $roleSetsDataType = 'array';// @codingStandardsIgnoreLine.
	/**
	 * For type variable
	 *
	 * @var $type .
	 */
	public $type;
	/**
	 * This function is used to set role
	 *
	 * @param string $roleSets .
	 */
	public function setRoleSets( $roleSets ) {// @codingStandardsIgnoreLine.
		$this->roleSets = $roleSets;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get role set
	 */
	public function getRoleSets() {
		return $this->roleSets;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set type
	 *
	 * @param string $type .
	 */
	public function setType( $type ) {
		$this->type = $type;
	}
	/**
	 * This function is used to get type
	 */
	public function getType() {
		return $this->type;
	}
}
/**
 * This class is used to addtional role info of role set
 */
class Google_Service_Drive_AboutAdditionalRoleInfoRoleSets extends Google_Collection {// @codingStandardsIgnoreLine.
	/**
	 * For collection key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'additionalRoles';
	/**
	 * For internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * For additional roles with public access
	 *
	 * @var $additionalRoles .
	 */
	public $additionalRoles;// @codingStandardsIgnoreLine.
	/**
	 * For primary role
	 *
	 * @var $primaryRole .
	 */
	public $primaryRole;// @codingStandardsIgnoreLine.
	/**
	 * This function is used to set additional roles
	 *
	 * @param string $additionalRoles .
	 */
	public function setAdditionalRoles( $additionalRoles ) {// @codingStandardsIgnoreLine.
		$this->additionalRoles = $additionalRoles;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get additional roles
	 */
	public function getAdditionalRoles() {
		return $this->additionalRoles;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set primary roles
	 *
	 * @param string $primaryRole .
	 */
	public function setPrimaryRole( $primaryRole ) {// @codingStandardsIgnoreLine.
		$this->primaryRole = $primaryRole;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set primary roles
	 */
	public function getPrimaryRole() {// @codingStandardsIgnoreLine.
		return $this->primaryRole;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used to export formats
 */
class Google_Service_Drive_AboutExportFormats extends Google_Collection {// @codingStandardsIgnoreLine.
	/**
	 * For collection key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'targets';
	/**
	 * For internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * For source variable
	 *
	 * @var $source .
	 */
	public $source;
	/**
	 * For targets variable access public
	 *
	 * @var $targets .
	 */
	public $targets;
	/**
	 * This function is used to set source
	 *
	 * @param string $source .
	 */
	public function setSource( $source ) {
		$this->source = $source;
	}
	/**
	 * This function is used to get source
	 */
	public function getSource() {
		return $this->source;
	}
	/**
	 * This function is used to set target
	 *
	 * @param string $targets .
	 */
	public function setTargets( $targets ) {
		$this->targets = $targets;
	}
	/**
	 * This function is used to get target
	 */
	public function getTargets() {
		return $this->targets;
	}
}
/**
 * This class is used for features of google servic drive
 */
class Google_Service_Drive_AboutFeatures extends Google_Model {// @codingStandardsIgnoreLine.
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for feature name
	 *
	 * @var $featureName .
	 */
	public $featureName;// @codingStandardsIgnoreLine.
	/**
	 * Variable for feature rate
	 *
	 * @var $featureRate .
	 */
	public $featureRate;// @codingStandardsIgnoreLine.
	/**
	 * Function for set feature name
	 *
	 * @param string $featureName .
	 */
	public function setFeatureName( $featureName ) {// @codingStandardsIgnoreLine.
		$this->featureName = $featureName;// @codingStandardsIgnoreLine.
	}
	/**
	 * Function for get feature name
	 */
	public function getFeatureName() {// @codingStandardsIgnoreLine.
		return $this->featureName;// @codingStandardsIgnoreLine.
	}
	/**
	 * Function for set feature rate
	 *
	 * @param string $featureRate .
	 */
	public function setFeatureRate( $featureRate ) {// @codingStandardsIgnoreLine.
		$this->featureRate = $featureRate;// @codingStandardsIgnoreLine.
	}
	/**
	 * Function for set feature name
	 */
	public function getFeatureRate() {// @codingStandardsIgnoreLine.
		return $this->featureRate;// @codingStandardsIgnoreLine.
	}
}
/**
 * Class for import formats
 */
class Google_Service_Drive_AboutImportFormats extends Google_Collection {// @codingStandardsIgnoreLine.
	/**
	 * For collection key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'targets';
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * For targets variable access public
	 *
	 * @var $targets .
	 */
	public $targets;
	/**
	 * This function is used to set source
	 *
	 * @param string $source .
	 */
	public function setSource( $source ) {
		$this->source = $source;
	}
	/**
	 * This function is used to get source
	 */
	public function getSource() {
		return $this->source;
	}
	/**
	 * This function is used to set target
	 *
	 * @param string $targets .
	 */
	public function setTargets( $targets ) {
		$this->targets = $targets;
	}
	/**
	 * This function is used to get target
	 */
	public function getTargets() {
		return $this->targets;
	}
}
/**
 * This class is used to upload max size
 */
class Google_Service_Drive_AboutMaxUploadSizes extends Google_Model {// @codingStandardsIgnoreLine.
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Use for size
	 *
	 * @var $size .
	 */
	public $size;
	/**
	 * Use for type
	 *
	 * @var $type .
	 */
	public $type;
	/**
	 * This function is used to set size
	 *
	 * @param int $size .
	 */
	public function setSize( $size ) {
		$this->size = $size;
	}
	/**
	 * This function is used to get size
	 */
	public function getSize() {
		return $this->size;
	}
	/**
	 * This function is used to set type
	 *
	 * @param string $type .
	 */
	public function setType( $type ) {
		$this->type = $type;
	}
	/**
	 * This function is used to get type
	 */
	public function getType() {
		return $this->type;
	}
}
class Google_Service_Drive_AboutQuotaBytesByService extends Google_Model {// @codingStandardsIgnoreLine.
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable used to byte
	 *
	 * @var $bytesUsed .
	 */
	public $bytesUsed;// @codingStandardsIgnoreLine.
	/**
	 * Name service
	 *
	 * @var $serviceName .
	 */
	public $serviceName;// @codingStandardsIgnoreLine.
	/**
	 * Function is used to  Set byte used
	 *
	 * @param string $bytesUsed .
	 */
	public function setBytesUsed( $bytesUsed ) {// @codingStandardsIgnoreLine.
		$this->bytesUsed = $bytesUsed;// @codingStandardsIgnoreLine.
	}
	/**
	 * Function is used to Get byte used
	 */
	public function getBytesUsed() {
		return $this->bytesUsed;// @codingStandardsIgnoreLine.
	}
	/**
	 * Function is used to  get service name
	 *
	 * @param string $serviceName .
	 */
	public function setServiceName( $serviceName ) {// @codingStandardsIgnoreLine.
		$this->serviceName = $serviceName;// @codingStandardsIgnoreLine.
	}
	/**
	 * Function is used to  get service name
	 */
	public function getServiceName() {
		return $this->serviceName;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used to extend google collecton .
 */
class Google_Service_Drive_App extends Google_Collection {// @codingStandardsIgnoreLine.
	/**
	 * For collection key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'secondaryMimeTypes';
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for authorized
	 *
	 * @var $authorized .
	 */
	public $authorized;
	/**
	 * Variable for create in folder template
	 *
	 * @var $createInFolderTemplate .
	 */
	public $createInFolderTemplate;// @codingStandardsIgnoreLine.
	/**
	 * Variable for create url
	 *
	 * @var $createUrl .
	 */
	public $createUrl;// @codingStandardsIgnoreLine.
	/**
	 * Variable for drive wide scope
	 *
	 * @var $createUrl .
	 */
	public $hasDriveWideScope;// @codingStandardsIgnoreLine.
	/**
	 * Variable for icon type
	 *
	 * @var $iconsType .
	 */
	protected $iconsType = 'Google_Service_Drive_AppIcons';// @codingStandardsIgnoreLine.
	/**
	 * Variable for icon data type
	 *
	 * @var $iconsDataType .
	 */
	protected $iconsDataType = 'array';// @codingStandardsIgnoreLine.
	/**
	 * Variable for id
	 *
	 * @var $id .
	 */
	public $id;
	/**
	 * Variable for installed
	 *
	 * @var $installed .
	 */
	public $installed;
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Variable for long description
	 *
	 * @var $longDescription .
	 */
	public $longDescription;// @codingStandardsIgnoreLine.
	/**
	 * Nmae variable with access public .
	 *
	 * @var $name .
	 */
	public $name;
	/**
	 * Variable for object type .
	 *
	 * @var $objectType .
	 */
	public $objectType;// @codingStandardsIgnoreLine.
	/**
	 * Variable for open Url Template .
	 *
	 * @var $openUrlTemplate .
	 */
	public $openUrlTemplate;// @codingStandardsIgnoreLine.
	/**
	 * Variable for primary File Extensions .
	 *
	 * @var $primaryFileExtensions .
	 */
	public $primaryFileExtensions;// @codingStandardsIgnoreLine.
	/**
	 * Variable for primary mime type .
	 *
	 * @var $primaryMimeTypes .
	 */
	public $primaryMimeTypes;// @codingStandardsIgnoreLine.
	/**
	 * Variable for product Id .
	 *
	 * @var $productId .
	 */
	public $productId;// @codingStandardsIgnoreLine.
	/**
	 * Variable for product url .
	 *
	 * @var $productUrl .
	 */
	public $productUrl;// @codingStandardsIgnoreLine.
	/**
	 * Variable for secondary File Extensions .
	 *
	 * @var $secondaryFileExtensions .
	 */
	public $secondaryFileExtensions;// @codingStandardsIgnoreLine.
	/**
	 * Variable for secondary MIME types .
	 *
	 * @var $secondaryMimeTypes .
	 */
	public $secondaryMimeTypes;// @codingStandardsIgnoreLine.
	/**
	 * Variable for short Description .
	 *
	 * @var $shortDescription .
	 */
	public $shortDescription;// @codingStandardsIgnoreLine.
	/**
	 * Variable for supports Create .
	 *
	 * @var $supportsCreate .
	 */
	public $supportsCreate;// @codingStandardsIgnoreLine.
	/**
	 * Variable for supports Import .
	 *
	 * @var $supportsImport .
	 */
	public $supportsImport;// @codingStandardsIgnoreLine.
	/**
	 * Variable for supports Multi Open .
	 *
	 * @var $supportsMultiOpen .
	 */
	public $supportsMultiOpen;// @codingStandardsIgnoreLine.
	/**
	 * Variable for supports Offline Create .
	 *
	 * @var $supportsOfflineCreate .
	 */
	public $supportsOfflineCreate;// @codingStandardsIgnoreLine.
	/**
	 * Variable for use By Default .
	 *
	 * @var $useByDefault .
	 */
	public $useByDefault;// @codingStandardsIgnoreLine.
	/**
	 * Function for set authorized .
	 *
	 * @param string $authorized .
	 */
	public function setAuthorized( $authorized ) {// @codingStandardsIgnoreLine.
		$this->authorized = $authorized;
	}
	/**
	 * Function for get authorized .
	 */
	public function getAuthorized() {
		return $this->authorized;// @codingStandardsIgnoreLine.
	}
	/**
	 * Function for set create in folder template.
	 *
	 * @param string $createInFolderTemplate .
	 */
	public function setCreateInFolderTemplate( $createInFolderTemplate ) {// @codingStandardsIgnoreLine.
		$this->createInFolderTemplate = $createInFolderTemplate;// @codingStandardsIgnoreLine.
	}
	/**
	 * Function for get create in folder template.
	 */
	public function getCreateInFolderTemplate() {
		return $this->createInFolderTemplate;// @codingStandardsIgnoreLine.
	}
	/**
	 * Function for set create url.
	 *
	 * @param string $createUrl .
	 */
	public function setCreateUrl( $createUrl ) {// @codingStandardsIgnoreLine.
		$this->createUrl = $createUrl;// @codingStandardsIgnoreLine.
	}
	/**
	 * Function for get create url.
	 */
	public function getCreateUrl() {
		return $this->createUrl;// @codingStandardsIgnoreLine.
	}
	/**
	 * Function for set drive wide scope.
	 *
	 * @param string $hasDriveWideScope .
	 */
	public function setHasDriveWideScope( $hasDriveWideScope ) {// @codingStandardsIgnoreLine.
		$this->hasDriveWideScope = $hasDriveWideScope;// @codingStandardsIgnoreLine.
	}
	/**
	 * Function for get drive wide scope
	 */
	public function getHasDriveWideScope() {
		return $this->hasDriveWideScope;// @codingStandardsIgnoreLine.
	}
	/**
	 * Function for set icons.
	 *
	 * @param string $icons .
	 */
	public function setIcons( $icons ) {
		$this->icons = $icons;
	}
	/**
	 * Function for get icons.
	 */
	public function getIcons() {
		return $this->icons;
	}
	/**
	 * Function for set id.
	 *
	 * @param int $id .
	 */
	public function setId( $id ) {
		$this->id = $id;
	}
	/**
	 * Function for get id.
	 */
	public function getId() {
		return $this->id;
	}
	/**
	 * Function for set installed.
	 *
	 * @param string $installed .
	 */
	public function setInstalled( $installed ) {
		$this->installed = $installed;
	}
	/**
	 * Function for set installed.
	 */
	public function getInstalled() {
		return $this->installed;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set long description
	 *
	 * @param string $longDescription .
	 */
	public function setLongDescription( $longDescription ) {// @codingStandardsIgnoreLine.
		$this->longDescription = $longDescription;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get long description
	 */
	public function getLongDescription() {
		return $this->longDescription;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set name
	 *
	 * @param string $name .
	 */
	public function setName( $name ) {
		$this->name = $name;
	}
	/**
	 * This function is used to get name
	 */
	public function getName() {
		return $this->name;
	}
	/**
	 * This function is used to set object type
	 *
	 * @param string $objectType .
	 */
	public function setObjectType( $objectType ) {// @codingStandardsIgnoreLine.
		$this->objectType = $objectType;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set object type
	 */
	public function getObjectType() {
		return $this->objectType;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set open url template
	 *
	 * @param string $openUrlTemplate .
	 */
	public function setOpenUrlTemplate( $openUrlTemplate ) {// @codingStandardsIgnoreLine.
		$this->openUrlTemplate = $openUrlTemplate;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get open url template
	 */
	public function getOpenUrlTemplate() {
		return $this->openUrlTemplate;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set primary File Extensions
	 *
	 * @param string $primaryFileExtensions .
	 */
	public function setPrimaryFileExtensions( $primaryFileExtensions ) {// @codingStandardsIgnoreLine.
		$this->primaryFileExtensions = $primaryFileExtensions;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get primary File Extensions
	 */
	public function getPrimaryFileExtensions() {
		return $this->primaryFileExtensions;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set primary Mime type
	 *
	 * @param string $primaryMimeTypes .
	 */
	public function setPrimaryMimeTypes( $primaryMimeTypes ) {// @codingStandardsIgnoreLine.
		$this->primaryMimeTypes = $primaryMimeTypes;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get primary Mime type
	 */
	public function getPrimaryMimeTypes() {
		return $this->primaryMimeTypes;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set product id
	 *
	 * @param string $productId .
	 */
	public function setProductId( $productId ) {// @codingStandardsIgnoreLine.
		$this->productId = $productId;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get product id
	 */
	public function getProductId() {
		return $this->productId;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set product url
	 *
	 * @param string $productUrl .
	 */
	public function setProductUrl( $productUrl ) {// @codingStandardsIgnoreLine.
		$this->productUrl = $productUrl;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get product url
	 */
	public function getProductUrl() {
		return $this->productUrl;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set secondary File Extensions
	 *
	 * @param string $secondaryFileExtensions .
	 */
	public function setSecondaryFileExtensions( $secondaryFileExtensions ) {// @codingStandardsIgnoreLine.
		$this->secondaryFileExtensions = $secondaryFileExtensions;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get secondary File Extensions
	 */
	public function getSecondaryFileExtensions() {
		return $this->secondaryFileExtensions;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set secondary Mime Types
	 *
	 * @param string $secondaryMimeTypes .
	 */
	public function setSecondaryMimeTypes( $secondaryMimeTypes ) {// @codingStandardsIgnoreLine.
		$this->secondaryMimeTypes = $secondaryMimeTypes;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get secondary Mime Types
	 */
	public function getSecondaryMimeTypes() {
		return $this->secondaryMimeTypes;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set short Description
	 *
	 * @param string $shortDescription .
	 */
	public function setShortDescription( $shortDescription ) {// @codingStandardsIgnoreLine.
		$this->shortDescription = $shortDescription;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get short Description
	 */
	public function getShortDescription() {
		return $this->shortDescription;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set supports create
	 *
	 * @param string $supportsCreate .
	 */
	public function setSupportsCreate( $supportsCreate ) {// @codingStandardsIgnoreLine.
		$this->supportsCreate = $supportsCreate;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get supports create
	 */
	public function getSupportsCreate() {
		return $this->supportsCreate;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set import
	 *
	 * @param string $supportsImport .
	 */
	public function setSupportsImport( $supportsImport ) {// @codingStandardsIgnoreLine.
		$this->supportsImport = $supportsImport;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get import
	 */
	public function getSupportsImport() {
		return $this->supportsImport;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set MultiOpen
	 *
	 * @param string $supportsMultiOpen .
	 */
	public function setSupportsMultiOpen( $supportsMultiOpen ) {// @codingStandardsIgnoreLine.
		$this->supportsMultiOpen = $supportsMultiOpen;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get MultiOpen
	 */
	public function getSupportsMultiOpen() {
		return $this->supportsMultiOpen;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set supports Offline Create
	 *
	 * @param string $supportsOfflineCreate .
	 */
	public function setSupportsOfflineCreate( $supportsOfflineCreate ) {// @codingStandardsIgnoreLine.
		$this->supportsOfflineCreate = $supportsOfflineCreate;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get offline create
	 */
	public function getSupportsOfflineCreate() {// @codingStandardsIgnoreLine.
		return $this->supportsOfflineCreate;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set use by default
	 *
	 * @param string $useByDefault .
	 */
	public function setUseByDefault( $useByDefault ) {// @codingStandardsIgnoreLine.
		$this->useByDefault = $useByDefault;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get use by default
	 */
	public function getUseByDefault() {
		return $this->useByDefault;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used for app icons
 *
 * @param string $secondaryFileExtensions .
 */
class Google_Service_Drive_AppIcons extends Google_Model {// @codingStandardsIgnoreLine.
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for category
	 *
	 * @var $category .
	 */
	public $category;
	/**
	 * Variable for iconUrl
	 *
	 * @var $iconUrl .
	 */
	public $iconUrl;// @codingStandardsIgnoreLine.
	/**
	 * Use for size
	 *
	 * @var $size .
	 */
	public $size;
	/**
	 * This function is used to set category
	 *
	 * @param string $category .
	 */
	public function setCategory( $category ) {
		$this->category = $category;
	}
	/**
	 * This function is used to get category
	 */
	public function getCategory() {
		return $this->category;
	}
	/**
	 * This function is used to set iconUrl
	 *
	 * @param string $iconUrl .
	 */
	public function setIconUrl( $iconUrl ) {// @codingStandardsIgnoreLine.
		$this->iconUrl = $iconUrl;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set iconUrl
	 */
	public function getIconUrl() {
		return $this->iconUrl;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set size
	 *
	 * @param int $size .
	 */
	public function setSize( $size ) {
		$this->size = $size;
	}
	/**
	 * This function is used to get size
	 */
	public function getSize() {
		return $this->size;
	}
}
/**
 * This class is used for app list
 */
class Google_Service_Drive_AppList extends Google_Collection {// @codingStandardsIgnoreLine.
	/**
	 * For collection key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'items';
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for default AppIds
	 *
	 * @var $defaultAppIds .
	 */
	public $defaultAppIds; // @codingStandardsIgnoreLine
	/**
	 * For variable etag
	 *
	 * @var $etag .
	 */
	public $etag;
	/**
	 * Variable for items Type
	 *
	 * @var $itemsType .
	 */
	protected $itemsType = 'Google_Service_Drive_App'; // @codingStandardsIgnoreLine
	/**
	 * Variable for items Data Type
	 *
	 * @var $itemsDataType .
	 */
	protected $itemsDataType = 'array'; // @codingStandardsIgnoreLine
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink; // @codingStandardsIgnoreLine
	/**
	 * This function for set default app id
	 *
	 * @param string $defaultAppIds .
	 */
	public function setDefaultAppIds( $defaultAppIds ) { // @codingStandardsIgnoreLine
		$this->defaultAppIds = $defaultAppIds; // @codingStandardsIgnoreLine
	}
	/**
	 * This function for get default app id
	 */
	public function getDefaultAppIds() {
		return $this->defaultAppIds; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set etag
	 *
	 * @param string $etag .
	 */
	public function setEtag( $etag ) {
		$this->etag = $etag;
	}
	/**
	 * This function is used to get etag
	 */
	public function getEtag() {
		return $this->etag;
	}
	/**
	 * This function is used to set items
	 *
	 * @param string $items .
	 */
	public function setItems( $items ) {
		$this->items = $items;
	}
	/**
	 * This function is used to get items
	 */
	public function getItems() {
		return $this->items;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used to change
 */
class Google_Service_Drive_Change extends Google_Model {// @codingStandardsIgnoreLine.
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for delete
	 *
	 * @var $deleted .
	 */
	public $deleted;
	/**
	 * Variable for file type
	 *
	 * @var $fileType .
	 */
	protected $fileType = 'Google_Service_Drive_DriveFile'; //@codingStandardsIgnoreLine
	/**
	 * Variable for file data type
	 *
	 * @var $fileDataType .
	 */
	protected $fileDataType = ''; //@codingStandardsIgnoreLine
	/**
	 * Variable for file id
	 *
	 * @var $fileId .
	 */
	public $fileId; //@codingStandardsIgnoreLine
	/**
	 * Variable for id
	 *
	 * @var $id .
	 */
	public $id;
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Variable with modification date
	 *
	 * @var $kind .
	 */
	public $modificationDate;//@codingStandardsIgnoreLine
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink; // @codingStandardsIgnoreLine
	/**
	 * This function is used for set delete
	 *
	 * @param string $deleted .
	 */
	public function setDeleted( $deleted ) {
		$this->deleted = $deleted;
	}
	/**
	 * This function is used for get delete
	 */
	public function getDeleted() {
		return $this->deleted;
	}
	/**
	 * This function is used for set file
	 *
	 * @param Google_Service_Drive_DriveFile $file .
	 */
	public function setFile( Google_Service_Drive_DriveFile $file ) {
		$this->file = $file;
	}
	/**
	 * This function is used for get file
	 */
	public function getFile() {
		return $this->file;
	}
	/**
	 * This function is used for set file id
	 *
	 * @param string $fileId .
	 */
	public function setFileId( $fileId ) { //@codingStandardsIgnoreLine
		$this->fileId = $fileId; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used for get file id
	 */
	public function getFileId() {
		return $this->fileId; // @codingStandardsIgnoreLine
	}
	/**
	 * Function for set id.
	 *
	 * @param int $id .
	 */
	public function setId( $id ) {
		$this->id = $id;
	}
	/**
	 * Function for get id.
	 */
	public function getId() {
		return $this->id;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * Function for set modification date.
	 *
	 * @param string $modificationDate .
	 */
	public function setModificationDate( $modificationDate ) { //@codingStandardsIgnoreLine
		$this->modificationDate = $modificationDate; // @codingStandardsIgnoreLine
	}
	/**
	 * Function for get modification date.
	 */
	public function getModificationDate() {
		return $this->modificationDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used to changelist
 */
class Google_Service_Drive_ChangeList extends Google_Collection {// @codingStandardsIgnoreLine.
	/**
	 * Collection of key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'items';
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * For variable etag
	 *
	 * @var $etag .
	 */
	public $etag;
	/**
	 * Variable for items Type
	 *
	 * @var $itemsType .
	 */
	protected $itemsType = 'Google_Service_Drive_Change'; // @codingStandardsIgnoreLine
	/**
	 * Variable for items Data Type
	 *
	 * @var $itemsDataType .
	 */
	protected $itemsDataType = 'array'; // @codingStandardsIgnoreLine
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Largest change id variable with access of public
	 *
	 * @var $largestChangeId .
	 */
	public $largestChangeId; //@codingStandardsIgnoreLine
	/**
	 * Next link variable with access of public
	 *
	 * @var $nextLink .
	 */
	public $nextLink;//@codingStandardsIgnoreLine
	/**
	 * Variable for next page token with access of public
	 *
	 * @var $nextPageToken .
	 */
	public $nextPageToken; //@codingStandardsIgnoreLine
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink; // @codingStandardsIgnoreLine
	/**
	 * This function is used to set etag
	 *
	 * @param string $etag .
	 */
	public function setEtag( $etag ) {
		$this->etag = $etag;
	}
	/**
	 * This function is used to get etag
	 */
	public function getEtag() {
		return $this->etag;
	}
	/**
	 * This function is used to set items
	 *
	 * @param string $items .
	 */
	public function setItems( $items ) {
		$this->items = $items;
	}
	/**
	 * This function is used to get items
	 */
	public function getItems() {
		return $this->items;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set largest change id
	 *
	 * @param string $largestChangeId .
	 */
	public function setLargestChangeId( $largestChangeId ) {// @codingStandardsIgnoreLine.
		$this->largestChangeId = $largestChangeId;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get largest change id
	 */
	public function getLargestChangeId() {
		return $this->largestChangeId; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set next link
	 *
	 * @param string $nextLink .
	 */
	public function setNextLink( $nextLink ) { //@codingStandardsIgnoreLine
		$this->nextLink = $nextLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get next link
	 */
	public function getNextLink() {
		return $this->nextLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set next page token
	 *
	 * @param string $nextPageToken .
	 */
	public function setNextPageToken( $nextPageToken ) { //@codingStandardsIgnoreLine
		$this->nextPageToken = $nextPageToken;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get next page token
	 */
	public function getNextPageToken() {
		return $this->nextPageToken;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used for channel
 */
class Google_Service_Drive_Channel extends Google_Model {// @codingStandardsIgnoreLine.
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for address
	 *
	 * @var $address .
	 */
	public $address;
	/**
	 * Variable for expiration
	 *
	 * @var $expiration .
	 */
	public $expiration;
	/**
	 * Variable for id
	 *
	 * @var $id .
	 */
	public $id;
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Variable for param
	 *
	 * @var $params .
	 */
	public $params;
	/**
	 * Variable for payload
	 *
	 * @var $payload .
	 */
	public $payload;
	/**
	 * Variable for resource Id
	 *
	 * @var $resourceId .
	 */
	public $resourceId; //@codingStandardsIgnoreLine
	/**
	 * Variable for resource Uri
	 *
	 * @var $resourceUri .
	 */
	public $resourceUri; //@codingStandardsIgnoreLine
	/**
	 * Variable for token
	 *
	 * @var $token .
	 */
	public $token;
	/**
	 * Use for type
	 *
	 * @var $type .
	 */
	public $type;
	/**
	 * This function is used to set address
	 *
	 * @param string $address .
	 */
	public function setAddress( $address ) {
		$this->address = $address;
	}
	/**
	 * This function is used to get address
	 */
	public function getAddress() {
		return $this->address;
	}
	/**
	 * This function is used to set expiration
	 *
	 * @param string $expiration .
	 */
	public function setExpiration( $expiration ) {
		$this->expiration = $expiration;
	}
	/**
	 * This function is used to get expiration
	 */
	public function getExpiration() {
		return $this->expiration;
	}
	/**
	 * Function for set id.
	 *
	 * @param int $id .
	 */
	public function setId( $id ) {
		$this->id = $id;
	}
	/**
	 * Function for get id.
	 */
	public function getId() {
		return $this->id;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set params
	 *
	 * @param string $params .
	 */
	public function setParams( $params ) {
		$this->params = $params;
	}
	/**
	 * This function is used to get params
	 */
	public function getParams() {
		return $this->params;
	}
	/**
	 * This function is used to set payload
	 *
	 * @param string $payload .
	 */
	public function setPayload( $payload ) {
		$this->payload = $payload;
	}
	/**
	 * This function is used to get payload
	 */
	public function getPayload() {
		return $this->payload;
	}
	/**
	 * This function is used to set resource Id
	 *
	 * @param string $resourceId .
	 */
	public function setResourceId( $resourceId ) { //@codingStandardsIgnoreLine
		$this->resourceId = $resourceId; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get resource Id
	 */
	public function getResourceId() {
		return $this->resourceId; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set resource uri
	 *
	 * @param string $resourceUri .
	 */
	public function setResourceUri( $resourceUri ) { //@codingStandardsIgnoreLine
		$this->resourceUri = $resourceUri; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get resource uri
	 */
	public function getResourceUri() {
		return $this->resourceUri; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set token
	 *
	 * @param string $token .
	 */
	public function setToken( $token ) {
		$this->token = $token;
	}
	/**
	 * This function is used to get token
	 */
	public function getToken() {
		return $this->token;
	}
	/**
	 * This function is used to set type
	 *
	 * @param string $type .
	 */
	public function setType( $type ) {
		$this->type = $type;
	}
	/**
	 * This function is used to get type
	 */
	public function getType() {
		return $this->type;
	}
}
/**
 * This class is used for channel params
 */
class Google_Service_Drive_ChannelParams extends Google_Model { // @codingStandardsIgnoreLine

}
/**
 * This class is used for child list
 */
class Google_Service_Drive_ChildList extends Google_Collection { // @codingStandardsIgnoreLine
	/**
	 * Collection of key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'items';
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * For variable etag
	 *
	 * @var $etag .
	 */
	public $etag;
	/**
	 * Variable for items Type
	 *
	 * @var $itemsType .
	 */
	protected $itemsType = 'Google_Service_Drive_ChildReference'; // @codingStandardsIgnoreLine
	/**
	 * Variable for items Data Type
	 *
	 * @var $itemsDataType .
	 */
	protected $itemsDataType = 'array'; // @codingStandardsIgnoreLine
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Next link variable with access of public
	 *
	 * @var $nextLink .
	 */
	public $nextLink; //@codingStandardsIgnoreLine
	/**
	 * Variable for next page token with access of public
	 *
	 * @var $nextPageToken .
	 */
	public $nextPageToken; //@codingStandardsIgnoreLine
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink;// @codingStandardsIgnoreLine
	/**
	 * This function is used to set etag
	 *
	 * @param string $etag .
	 */
	public function setEtag( $etag ) {
		$this->etag = $etag;
	}
	/**
	 * This function is used to get etag
	 */
	public function getEtag() {
		return $this->etag;
	}
	/**
	 * This function is used to set items
	 *
	 * @param string $items .
	 */
	public function setItems( $items ) {
		$this->items = $items;
	}
	/**
	 * This function is used to get items
	 */
	public function getItems() {
		return $this->items;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set next link
	 *
	 * @param string $nextLink .
	 */
	public function setNextLink( $nextLink ) { //@codingStandardsIgnoreLine
		$this->nextLink = $nextLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get next link
	 */
	public function getNextLink() {
		return $this->nextLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set next page token
	 *
	 * @param string $nextPageToken .
	 */
	public function setNextPageToken( $nextPageToken ) { //@codingStandardsIgnoreLine
		$this->nextPageToken = $nextPageToken;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get next page token
	 */
	public function getNextPageToken() {
		return $this->nextPageToken;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used for child referenece
 */
class Google_Service_Drive_ChildReference extends Google_Model { // @codingStandardsIgnoreLine
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for child Link
	 *
	 * @var $childLink .
	 */
	public $childLink; //@codingStandardsIgnoreLine
	/**
	 * Variable for id
	 *
	 * @var $id .
	 */
	public $id;
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink;// @codingStandardsIgnoreLine
	/**
	 * Function for set child link
	 *
	 * @param string $childLink .
	 */
	public function setChildLink( $childLink ) { //@codingStandardsIgnoreLine
		$this->childLink = $childLink; // @codingStandardsIgnoreLine
	}
	/**
	 * Function for get child link
	 */
	public function getChildLink() {
		return $this->childLink; // @codingStandardsIgnoreLine
	}
	/**
	 * Function for set id.
	 *
	 * @param int $id .
	 */
	public function setId( $id ) {
		$this->id = $id;
	}
	/**
	 * Function for get id.
	 */
	public function getId() {
		return $this->id;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used for comment
 */
class Google_Service_Drive_Comment extends Google_Collection { // @codingStandardsIgnoreLine
	/**
	 * Collection of key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'replies';
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Anchor variable with public access
	 *
	 * @var $anchor .
	 */
	public $anchor;
	/**
	 * Variable for author type
	 *
	 * @var $authorType .
	 */
	protected $authorType = 'Google_Service_Drive_User'; //@codingStandardsIgnoreLine
	/**
	 * Variable for author data type
	 *
	 * @var $authorDataType .
	 */
	protected $authorDataType = ''; //@codingStandardsIgnoreLine
	/**
	 * Variable for comment id
	 *
	 * @var $commentId .
	 */
	public $commentId; //@codingStandardsIgnoreLine
	/**
	 * Variable for content
	 *
	 * @var $content .
	 */
	public $content;
	/**
	 * Variable for context type
	 *
	 * @var $contextType .
	 */
	protected $contextType = 'Google_Service_Drive_CommentContext'; //@codingStandardsIgnoreLine
	/**
	 * Variable for context data type
	 *
	 * @var $contextDataType .
	 */
	protected $contextDataType = ''; //@codingStandardsIgnoreLine
	/**
	 * Variable for create date
	 *
	 * @var $createdDate .
	 */
	public $createdDate; //@codingStandardsIgnoreLine
	/**
	 * Variable for delete
	 *
	 * @var $deleted .
	 */
	public $deleted;
	/**
	 * Variable for file id
	 *
	 * @var $fileId .
	 */
	public $fileId; //@codingStandardsIgnoreLine
	/**
	 * Variable for file title
	 *
	 * @var $fileTitle .
	 */
	public $fileTitle; //@codingStandardsIgnoreLine
	/**
	 * Variable for html Content
	 *
	 * @var $htmlContent .
	 */
	public $htmlContent; //@codingStandardsIgnoreLine
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Variable for modified Date
	 *
	 * @var $modifiedDate .
	 */
	public $modifiedDate; //@codingStandardsIgnoreLine
	/**
	 * Variable for replies type
	 *
	 * @var $repliesType .
	 */
	protected $repliesType = 'Google_Service_Drive_CommentReply'; //@codingStandardsIgnoreLine
	/**
	 * Variable for replies data type
	 *
	 * @var $repliesDataType .
	 */
	protected $repliesDataType = 'array'; //@codingStandardsIgnoreLine
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink;// @codingStandardsIgnoreLine
	/**
	 * Status variable with public access
	 *
	 * @var $status .
	 */
	public $status;
	/**
	 * Function is for set anchor .
	 *
	 * @param string $anchor .
	 */
	public function setAnchor( $anchor ) {
		$this->anchor = $anchor;
	}
	/**
	 * Function is for get anchor .
	 */
	public function getAnchor() {
		return $this->anchor;
	}
	/**
	 * Function is for set author
	 *
	 * @param Google_Service_Drive_User $author .
	 */
	public function setAuthor( Google_Service_Drive_User $author ) {
		$this->author = $author;
	}
	/**
	 * Function is for get author
	 */
	public function getAuthor() {
		return $this->author;
	}
	/**
	 * Function is for set comment id
	 *
	 * @param string $commentId .
	 */
	public function setCommentId( $commentId ) { //@codingStandardsIgnoreLine
		$this->commentId = $commentId; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for get comment id
	 */
	public function getCommentId() {
		return $this->commentId; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for set content
	 *
	 * @param string $content .
	 */
	public function setContent( $content ) {
		$this->content = $content;
	}
	/**
	 * Function is for get content .
	 */
	public function getContent() {
		return $this->content;
	}
	/**
	 * Function is for set context
	 *
	 * @param Google_Service_Drive_CommentContext $context .
	 */
	public function setContext( Google_Service_Drive_CommentContext $context ) {
		$this->context = $context;
	}
	/**
	 * Function is for set context
	 */
	public function getContext() {
		return $this->context;
	}
	/**
	 * Function is for set ceate date
	 *
	 * @param string $createdDate .
	 */
	public function setCreatedDate( $createdDate ) {//@codingStandardsIgnoreLine
		$this->createdDate = $createdDate; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for get create Date
	 */
	public function getCreatedDate() {
		return $this->createdDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used for set delete
	 *
	 * @param string $deleted .
	 */
	public function setDeleted( $deleted ) {
		$this->deleted = $deleted;
	}
	/**
	 * This function is used for get delete
	 */
	public function getDeleted() {
		return $this->deleted;
	}
	/**
	 * This function is used for set file id
	 *
	 * @param string $fileId .
	 */
	public function setFileId( $fileId ) { //@codingStandardsIgnoreLine
		$this->fileId = $fileId; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used for get file id
	 */
	public function getFileId() {
		return $this->fileId; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set file title
	 *
	 * @param string $fileTitle .
	 */
	public function setFileTitle( $fileTitle ) { //@codingStandardsIgnoreLine
		$this->fileTitle = $fileTitle; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get file title
	 */
	public function getFileTitle() {
		return $this->fileTitle; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set html Content
	 *
	 * @param string $htmlContent .
	 */
	public function setHtmlContent( $htmlContent ) { //@codingStandardsIgnoreLine
		$this->htmlContent = $htmlContent; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used for get file id
	 */
	public function getHtmlContent() {
		return $this->htmlContent; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set modified Date
	 *
	 * @param string $modifiedDate .
	 */
	public function setModifiedDate( $modifiedDate ) { //@codingStandardsIgnoreLine
		$this->modifiedDate = $modifiedDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get modified Date
	 */
	public function getModifiedDate() {
		return $this->modifiedDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set replies
	 *
	 * @param string $replies .
	 */
	public function setReplies( $replies ) {
		$this->replies = $replies;
	}
	/**
	 * This function is used to get replies
	 */
	public function getReplies() {
		return $this->replies;
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set status
	 *
	 * @param string $status .
	 */
	public function setStatus( $status ) {
		$this->status = $status;
	}
	/**
	 * This function is used to get status
	 */
	public function getStatus() {
		return $this->status;
	}
}
/**
 * This class is used for comment context
 */
class Google_Service_Drive_CommentContext extends Google_Model { // @codingStandardsIgnoreLine
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable use for type
	 *
	 * @var $type .
	 */
	public $type;
	/**
	 * Variable use for value
	 *
	 * @var $value .
	 */
	public $value;
	/**
	 * This function is used to set type
	 *
	 * @param string $type .
	 */
	public function setType( $type ) {
		$this->type = $type;
	}
	/**
	 * This function is used to get type
	 */
	public function getType() {
		return $this->type;
	}
	/**
	 * This function is used to set value
	 *
	 * @param string $value .
	 */
	public function setValue( $value ) {
		$this->value = $value;
	}
	/**
	 * This function is used to get value
	 */
	public function getValue() {
		return $this->value;
	}
}
/**
 * This class is used for comment list
 */
class Google_Service_Drive_CommentList extends Google_Collection { // @codingStandardsIgnoreLine
	/**
	 * Collection of key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'items';
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for items Type
	 *
	 * @var $itemsType .
	 */
	protected $itemsType = 'Google_Service_Drive_Comment'; // @codingStandardsIgnoreLine
	/**
	 * Variable for items Data Type
	 *
	 * @var $itemsDataType .
	 */
	protected $itemsDataType = 'array'; // @codingStandardsIgnoreLine
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Next link variable with access of public
	 *
	 * @var $nextLink .
	 */
	public $nextLink; //@codingStandardsIgnoreLine
	/**
	 * Variable for next page token with access of public
	 *
	 * @var $nextPageToken .
	 */
	public $nextPageToken; //@codingStandardsIgnoreLine
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink;// @codingStandardsIgnoreLine
	/**
	 * This function is used to set items
	 *
	 * @param string $items .
	 */
	public function setItems( $items ) {
		$this->items = $items;
	}
	/**
	 * This function is used to get items
	 */
	public function getItems() {
		return $this->items;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set next link
	 *
	 * @param string $nextLink .
	 */
	public function setNextLink( $nextLink ) { //@codingStandardsIgnoreLine
		$this->nextLink = $nextLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get next link
	 */
	public function getNextLink() {
		return $this->nextLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set next page token
	 *
	 * @param string $nextPageToken .
	 */
	public function setNextPageToken( $nextPageToken ) { //@codingStandardsIgnoreLine
		$this->nextPageToken = $nextPageToken;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get next page token
	 */
	public function getNextPageToken() {
		return $this->nextPageToken;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used for comment reply
 */
class Google_Service_Drive_CommentReply extends Google_Model { // @codingStandardsIgnoreLine
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for author type
	 *
	 * @var $authorType .
	 */
	protected $authorType = 'Google_Service_Drive_User'; //@codingStandardsIgnoreLine
	/**
	 * Variable for author data type
	 *
	 * @var $authorDataType .
	 */
	protected $authorDataType = ''; //@codingStandardsIgnoreLine
	/**
	 * Variable for content
	 *
	 * @var $content .
	 */
	public $content;
	/**
	 * Variable for create date
	 *
	 * @var $createdDate .
	 */
	public $createdDate; //@codingStandardsIgnoreLine
	/**
	 * Variable for delete
	 *
	 * @var $deleted .
	 */
	public $deleted;
	/**
	 * Variable for html Content
	 *
	 * @var $htmlContent .
	 */
	public $htmlContent; //@codingStandardsIgnoreLine
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Variable for modified Date
	 *
	 * @var $modifiedDate .
	 */
	public $modifiedDate; //@codingStandardsIgnoreLine
	/**
	 * Variable for reply id
	 *
	 * @var $replyId .
	 */
	public $replyId; //@codingStandardsIgnoreLine
	/**
	 * Variable for verb
	 *
	 * @var $verb .
	 */
	public $verb;
	/**
	 * Function is for set author
	 *
	 * @param Google_Service_Drive_User $author .
	 */
	public function setAuthor( Google_Service_Drive_User $author ) {
		$this->author = $author;
	}
	/**
	 * Function is for get author
	 */
	public function getAuthor() {
		return $this->author;
	}
	/**
	 * Function is for set content
	 *
	 * @param string $content .
	 */
	public function setContent( $content ) {
		$this->content = $content;
	}
	/**
	 * Function is for get content .
	 */
	public function getContent() {
		return $this->content;
	}
	/**
	 * Function is for set ceate date
	 *
	 * @param string $createdDate .
	 */
	public function setCreatedDate( $createdDate ) {//@codingStandardsIgnoreLine
		$this->createdDate = $createdDate; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for get create Date
	 */
	public function getCreatedDate() {
		return $this->createdDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used for set delete
	 *
	 * @param string $deleted .
	 */
	public function setDeleted( $deleted ) {
		$this->deleted = $deleted;
	}
	/**
	 * This function is used for get delete
	 */
	public function getDeleted() {
		return $this->deleted;
	}
	/**
	 * This function is used to set html Content
	 *
	 * @param string $htmlContent .
	 */
	public function setHtmlContent( $htmlContent ) { //@codingStandardsIgnoreLine
		$this->htmlContent = $htmlContent; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get html Content
	 */
	public function getHtmlContent() {
		return $this->htmlContent; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set modified Date
	 *
	 * @param string $modifiedDate .
	 */
	public function setModifiedDate( $modifiedDate ) { //@codingStandardsIgnoreLine
		$this->modifiedDate = $modifiedDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get modified Date
	 */
	public function getModifiedDate() {
		return $this->modifiedDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set reply id
	 *
	 * @param string $replyId .
	 */
	public function setReplyId( $replyId ) { //@codingStandardsIgnoreLine
		$this->replyId = $replyId; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get reply id
	 */
	public function getReplyId() {
		return $this->replyId; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set verb
	 *
	 * @param string $verb .
	 */
	public function setVerb( $verb ) {
		$this->verb = $verb;
	}
	/**
	 * This function is used to get verb
	 */
	public function getVerb() {
		return $this->verb;
	}
}
/**
 * This class is used for comment reply list
 */
class Google_Service_Drive_CommentReplyList extends Google_Collection { // @codingStandardsIgnoreLine
	/**
	 * Collection of key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'items';
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for items Type
	 *
	 * @var $itemsType .
	 */
	protected $itemsType = 'Google_Service_Drive_CommentReply'; // @codingStandardsIgnoreLine
	/**
	 * Variable for items Data Type
	 *
	 * @var $itemsDataType .
	 */
	protected $itemsDataType = 'array'; //@codingStandardsIgnoreLine
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Next link variable with access of public
	 *
	 * @var $nextLink .
	 */
	public $nextLink; //@codingStandardsIgnoreLine
	/**
	 * Variable for next page token with access of public
	 *
	 * @var $nextPageToken .
	 */
	public $nextPageToken; //@codingStandardsIgnoreLine
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink;// @codingStandardsIgnoreLine
	/**
	 * This function is used to set items
	 *
	 * @param string $items .
	 */
	public function setItems( $items ) {
		$this->items = $items;
	}
	/**
	 * This function is used to get items
	 */
	public function getItems() {
		return $this->items;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set next link
	 *
	 * @param string $nextLink .
	 */
	public function setNextLink( $nextLink ) { //@codingStandardsIgnoreLine
		$this->nextLink = $nextLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get next link
	 */
	public function getNextLink() {
		return $this->nextLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set next page token
	 *
	 * @param string $nextPageToken .
	 */
	public function setNextPageToken( $nextPageToken ) { //@codingStandardsIgnoreLine
		$this->nextPageToken = $nextPageToken;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get next page token
	 */
	public function getNextPageToken() {
		return $this->nextPageToken;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used for drive file
 */
class Google_Service_Drive_DriveFile extends Google_Collection {// @codingStandardsIgnoreLine
	/**
	 * Collection of key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'properties';
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for alternate Link
	 *
	 * @var $alternateLink .
	 */
	public $alternateLink;//@codingStandardsIgnoreLine
	/**
	 * Variable for app data content
	 *
	 * @var $appDataContents .
	 */
	public $appDataContents; //@codingStandardsIgnoreLine
	/**
	 * Variable for copy
	 *
	 * @var $copyable .
	 */
	public $copyable;
	/**
	 * Variable for create date
	 *
	 * @var $createdDate .
	 */
	public $createdDate; //@codingStandardsIgnoreLine
	/**
	 * Variable for default link
	 *
	 * @var $defaultOpenWithLink .
	 */
	public $defaultOpenWithLink; //@codingStandardsIgnoreLine
	/**
	 * Variable for description
	 *
	 * @var $description .
	 */
	public $description;
	/**
	 * Variable for download Url
	 *
	 * @var $downloadUrl .
	 */
	public $downloadUrl; //@codingStandardsIgnoreLine
	/**
	 * Variable for editable
	 *
	 * @var $editable .
	 */
	public $editable;
	/**
	 * Variable for embed Link
	 *
	 * @var $embedLink .
	 */
	public $embedLink; //@codingStandardsIgnoreLine
	/**
	 * For variable etag
	 *
	 * @var $etag .
	 */
	public $etag;
	/**
	 * For variable explicitly Trashed
	 *
	 * @var $explicitlyTrashed .
	 */
	public $explicitlyTrashed; //@codingStandardsIgnoreLine
	/**
	 * For variable export Links
	 *
	 * @var $exportLinks .
	 */
	public $exportLinks; //@codingStandardsIgnoreLine
	/**
	 * For variable file Extension
	 *
	 * @var $fileExtension .
	 */
	public $fileExtension; //@codingStandardsIgnoreLine
	/**
	 * For variable file Size
	 *
	 * @var $fileSize .
	 */
	public $fileSize; //@codingStandardsIgnoreLine
	/**
	 * For variable folder Color Rgb
	 *
	 * @var $folderColorRgb .
	 */
	public $folderColorRgb; //@codingStandardsIgnoreLine
	/**
	 * For variable head Revision Id
	 *
	 * @var $headRevisionId .
	 */
	public $headRevisionId; //@codingStandardsIgnoreLine
	/**
	 * For variable icon Link
	 *
	 * @var $iconLink .
	 */
	public $iconLink; //@codingStandardsIgnoreLine
	/**
	 * Variable for id
	 *
	 * @var $id .
	 */
	public $id;
	/**
	 * Variable for image Media Meta data Type
	 *
	 * @var $imageMediaMetadataType .
	 */
	protected $imageMediaMetadataType = 'Google_Service_Drive_DriveFileImageMediaMetadata'; //@codingStandardsIgnoreLine
	/**
	 * Variable for image Media Meta data Type
	 *
	 * @var $imageMediaMetadataDataType .
	 */
	protected $imageMediaMetadataDataType = ''; //@codingStandardsIgnoreLine
	/**
	 * Variable for indexable Text Type
	 *
	 * @var $indexableTextType .
	 */
	protected $indexableTextType = 'Google_Service_Drive_DriveFileIndexableText'; //@codingStandardsIgnoreLine
	/**
	 * Variable for indexable Text data Type
	 *
	 * @var $indexableTextDataType .
	 */
	protected $indexableTextDataType = ''; //@codingStandardsIgnoreLine
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Variable for labels Type
	 *
	 * @var $labelsType .
	 */
	protected $labelsType = 'Google_Service_Drive_DriveFileLabels'; //@codingStandardsIgnoreLine
	/**
	 * Variable for labels data Type
	 *
	 * @var $labelsDataType .
	 */
	protected $labelsDataType = ''; //@codingStandardsIgnoreLine
	/**
	 * Variable for last Modifying User Type
	 *
	 * @var $lastModifyingUserType .
	 */
	protected $lastModifyingUserType = 'Google_Service_Drive_User'; //@codingStandardsIgnoreLine
	/**
	 * Variable for last Modifying User data Type
	 *
	 * @var $lastModifyingUserDataType .
	 */
	protected $lastModifyingUserDataType = ''; //@codingStandardsIgnoreLine
	/**
	 * Variable for last Modifying User Name
	 *
	 * @var $lastModifyingUserName .
	 */
	public $lastModifyingUserName; //@codingStandardsIgnoreLine
	/**
	 * Variable for lastViewed By Me Date
	 *
	 * @var $lastViewedByMeDate .
	 */
	public $lastViewedByMeDate; //@codingStandardsIgnoreLine
	/**
	 * Variable for marked Viewed By Date
	 *
	 * @var $markedViewedByMeDate .
	 */
	public $markedViewedByMeDate; //@codingStandardsIgnoreLine
	/**
	 * Variable for md5 Checksum
	 *
	 * @var $md5Checksum .
	 */
	public $md5Checksum; //@codingStandardsIgnoreLine
	/**
	 * Variable for mime Type
	 *
	 * @var $mimeType .
	 */
	public $mimeType; //@codingStandardsIgnoreLine
	/**
	 * Variable for  modified ByMe Date
	 *
	 * @var $modifiedByMeDate .
	 */
	public $modifiedByMeDate; //@codingStandardsIgnoreLine
	/**
	 * Variable for modified Date
	 *
	 * @var $modifiedDate .
	 */
	public $modifiedDate; //@codingStandardsIgnoreLine
	/**
	 * Variable for open With Links
	 *
	 * @var $openWithLinks .
	 */
	public $openWithLinks; //@codingStandardsIgnoreLine
	/**
	 * Variable for original File name
	 *
	 * @var $originalFilename .
	 */
	public $originalFilename; //@codingStandardsIgnoreLine
	/**
	 * Variable for  owner Names
	 *
	 * @var $ownerNames .
	 */
	public $ownerNames; //@codingStandardsIgnoreLine
	/**
	 * Variable for owners Type
	 *
	 * @var $ownersType .
	 */
	protected $ownersType = 'Google_Service_Drive_User'; //@codingStandardsIgnoreLine
	/**
	 * Variable for owners Data Type
	 *
	 * @var $ownersDataType .
	 */
	protected $ownersDataType = 'array'; //@codingStandardsIgnoreLine
	/**
	 * Variable for parents Type
	 *
	 * @var $parentsType .
	 */
	protected $parentsType = 'Google_Service_Drive_ParentReference'; //@codingStandardsIgnoreLine
	/**
	 * Variable for parents Data Type
	 *
	 * @var $parentsDataType .
	 */
	protected $parentsDataType = 'array'; //@codingStandardsIgnoreLine
	/**
	 * Variable for permissions Type
	 *
	 * @var $permissionsType .
	 */
	protected $permissionsType = 'Google_Service_Drive_Permission'; //@codingStandardsIgnoreLine
	/**
	 * Variable for permissions Data Type
	 *
	 * @var $permissionsDataType .
	 */
	protected $permissionsDataType = 'array'; //@codingStandardsIgnoreLine
	/**
	 * Variable for properties Type
	 *
	 * @var $propertiesType .
	 */
	protected $propertiesType = 'Google_Service_Drive_Property'; //@codingStandardsIgnoreLine
	/**
	 * Variable for properties Data Type
	 *
	 * @var $propertiesDataType .
	 */
	protected $propertiesDataType = 'array'; //@codingStandardsIgnoreLine
	/**
	 * Quota byte used with public access
	 *
	 * @var $quotaBytesUsed .
	 */
	public $quotaBytesUsed; //@codingStandardsIgnoreLine
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink;// @codingStandardsIgnoreLine
	/**
	 * Variable for shared
	 *
	 * @var $shared .
	 */
	public $shared;
	/**
	 * Variable for shared With Me Date
	 *
	 * @var $sharedWithMeDate .
	 */
	public $sharedWithMeDate; //@codingStandardsIgnoreLine
	/**
	 * Variable for sharing User Type
	 *
	 * @var $sharingUserType .
	 */
	protected $sharingUserType = 'Google_Service_Drive_User'; //@codingStandardsIgnoreLine
	/**
	 * Variable for sharing User Data Type
	 *
	 * @var $sharingUserDataType .
	 */
	protected $sharingUserDataType = '';//@codingStandardsIgnoreLine
	/**
	 * Variable for thumbnail Type
	 *
	 * @var $thumbnailType .
	 */
	protected $thumbnailType = 'Google_Service_Drive_DriveFileThumbnail'; //@codingStandardsIgnoreLine
	/**
	 * Variable for thumbnail Data Type
	 *
	 * @var $thumbnailDataType .
	 */
	protected $thumbnailDataType = ''; //@codingStandardsIgnoreLine
	/**
	 * Variable for thumbnal Link
	 *
	 * @var $thumbnailLink .
	 */
	public $thumbnailLink; //@codingStandardsIgnoreLine
	/**
	 * Variable for title
	 *
	 * @var $title .
	 */
	public $title;
	/**
	 * Variable for  user Permission Type
	 *
	 * @var $userPermissionType .
	 */
	protected $userPermissionType = 'Google_Service_Drive_Permission'; //@codingStandardsIgnoreLine
	/**
	 * Variable for user Permission Data Type
	 *
	 * @var $userPermissionDataType .
	 */
	protected $userPermissionDataType = ''; //@codingStandardsIgnoreLine
	/**
	 * Variable for version
	 *
	 * @var $version .
	 */
	public $version;
	/**
	 * Variable for video Media Meta Data Type
	 *
	 * @var $videoMediaMetadataType .
	 */
	protected $videoMediaMetadataType = 'Google_Service_Drive_DriveFileVideoMediaMetadata'; //@codingStandardsIgnoreLine
	/**
	 * Variable for video Media Meta Data Type
	 *
	 * @var $videoMediaMetadataDataType .
	 */
	protected $videoMediaMetadataDataType = ''; //@codingStandardsIgnoreLine
	/**
	 * Variable for web Content Link
	 *
	 * @var $webContentLink .
	 */
	public $webContentLink; //@codingStandardsIgnoreLine
	/**
	 * Variable for web View Link
	 *
	 * @var $webViewLink .
	 */
	public $webViewLink; //@codingStandardsIgnoreLine
	/**
	 * Variable for writers Can Share
	 *
	 * @var $writersCanShare .
	 */
	public $writersCanShare;//@codingStandardsIgnoreLine
	/**
	 * This function is used to set alternate link
	 *
	 * @param string $alternateLink .
	 */
	public function setAlternateLink( $alternateLink ) { //@codingStandardsIgnoreLine
		$this->alternateLink = $alternateLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get alternate link
	 */
	public function getAlternateLink() {
		return $this->alternateLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set app Data Contents
	 *
	 * @param string $appDataContents .
	 */
	public function setAppDataContents( $appDataContents ) { //@codingStandardsIgnoreLine
		$this->appDataContents = $appDataContents; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set app Data Contents
	 */
	public function getAppDataContents() {
		return $this->appDataContents; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set  copyable
	 *
	 * @param string $copyable .
	 */
	public function setCopyable( $copyable ) {
		$this->copyable = $copyable;
	}
	/**
	 * This function is used to set copyable
	 */
	public function getCopyable() {
		return $this->copyable;
	}
	/**
	 * Function is for set ceate date
	 *
	 * @param string $createdDate .
	 */
	public function setCreatedDate( $createdDate ) { //@codingStandardsIgnoreLine
		$this->createdDate = $createdDate; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for get create Date
	 */
	public function getCreatedDate() {
		return $this->createdDate; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for set default Open With Link
	 *
	 * @param string $defaultOpenWithLink .
	 */
	public function setDefaultOpenWithLink( $defaultOpenWithLink ) { //@codingStandardsIgnoreLine
		$this->defaultOpenWithLink = $defaultOpenWithLink; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for get default Open With Link
	 */
	public function getDefaultOpenWithLink() {
		return $this->defaultOpenWithLink; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for set description
	 *
	 * @param string $description .
	 */
	public function setDescription( $description ) {
		$this->description = $description;
	}
	/**
	 * Function is for get description
	 */
	public function getDescription() {
		return $this->description;
	}
	/**
	 * Function is for  download Url
	 *
	 * @param string $downloadUrl .
	 */
	public function setDownloadUrl( $downloadUrl ) { //@codingStandardsIgnoreLine
		$this->downloadUrl = $downloadUrl; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for download Url
	 */
	public function getDownloadUrl() {
		return $this->downloadUrl; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for set editable
	 *
	 * @param string $editable .
	 */
	public function setEditable( $editable ) {
		$this->editable = $editable;
	}
	/**
	 * Function is for get editable
	 */
	public function getEditable() {
		return $this->editable;
	}
	/**
	 * Function is for set embed Link
	 *
	 * @param string $embedLink .
	 */
	public function setEmbedLink( $embedLink ) { //@codingStandardsIgnoreLine
		$this->embedLink = $embedLink; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for get embed Link
	 */
	public function getEmbedLink() {
		return $this->embedLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set etag
	 *
	 * @param string $etag .
	 */
	public function setEtag( $etag ) {
		$this->etag = $etag;
	}
	/**
	 * This function is used to get etag
	 */
	public function getEtag() {
		return $this->etag;
	}
	/**
	 * Function is for set explicitly Trashed
	 *
	 * @param string $explicitlyTrashed .
	 */
	public function setExplicitlyTrashed( $explicitlyTrashed ) { //@codingStandardsIgnoreLine
		$this->explicitlyTrashed = $explicitlyTrashed; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for get explicitly Trashed
	 */
	public function getExplicitlyTrashed() {
		return $this->explicitlyTrashed; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for set export Links
	 *
	 * @param string $exportLinks .
	 */
	public function setExportLinks( $exportLinks ) { //@codingStandardsIgnoreLine
		$this->exportLinks = $exportLinks; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for get export Links
	 */
	public function getExportLinks() {
		return $this->exportLinks; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for set file Extension
	 *
	 * @param string $fileExtension .
	 */
	public function setFileExtension( $fileExtension ) { //@codingStandardsIgnoreLine
		$this->fileExtension = $fileExtension; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for get file Extension
	 */
	public function getFileExtension() {
		return $this->fileExtension; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for set file Size
	 *
	 * @param string $fileSize .
	 */
	public function setFileSize( $fileSize ) { // @codingStandardsIgnoreLine
		$this->fileSize = $fileSize; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for get file Size
	 */
	public function getFileSize() {
		return $this->fileSize; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for set folder for Color Rgb
	 *
	 * @param string $folderColorRgb .
	 */
	public function setFolderColorRgb( $folderColorRgb ) { // @codingStandardsIgnoreLine
		$this->folderColorRgb = $folderColorRgb; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for get folder for Color Rgb
	 */
	public function getFolderColorRgb() {
		return $this->folderColorRgb; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for set head Revision Id
	 *
	 * @param string $headRevisionId .
	 */
	public function setHeadRevisionId( $headRevisionId ) { // @codingStandardsIgnoreLine
		$this->headRevisionId = $headRevisionId; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for get head Revision Id
	 */
	public function getHeadRevisionId() {
		return $this->headRevisionId; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for set icon Link
	 *
	 * @param string $iconLink .
	 */
	public function setIconLink( $iconLink ) { // @codingStandardsIgnoreLine
		$this->iconLink = $iconLink; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for get icon Link
	 */
	public function getIconLink() {
		return $this->iconLink; // @codingStandardsIgnoreLine
	}
	/**
	 * Function for set id.
	 *
	 * @param int $id .
	 */
	public function setId( $id ) {
		$this->id = $id;
	}
	/**
	 * Function for get id.
	 */
	public function getId() {
		return $this->id;
	}
	/**
	 * Function for set image Media Meta data
	 *
	 * @param Google_Service_Drive_DriveFileImageMediaMetadata $imageMediaMetadata .
	 */
	public function setImageMediaMetadata( Google_Service_Drive_DriveFileImageMediaMetadata $imageMediaMetadata ) { // @codingStandardsIgnoreLine
		$this->imageMediaMetadata = $imageMediaMetadata; // @codingStandardsIgnoreLine
	}
	/**
	 * Function for set image Media Meta data
	 */
	public function getImageMediaMetadata() {
		return $this->imageMediaMetadata; // @codingStandardsIgnoreLine
	}
	/**
	 * Function for set indexable Text.
	 *
	 * @param Google_Service_Drive_DriveFileIndexableText $indexableText .
	 */
	public function setIndexableText( Google_Service_Drive_DriveFileIndexableText $indexableText ) { // @codingStandardsIgnoreLine
		$this->indexableText = $indexableText; // @codingStandardsIgnoreLine
	}
	/**
	 * Function for set indexable Text.
	 */
	public function getIndexableText() {
		return $this->indexableText; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set labels
	 *
	 * @param Google_Service_Drive_DriveFileLabels $labels .
	 */
	public function setLabels( Google_Service_Drive_DriveFileLabels $labels ) {
		$this->labels = $labels;
	}
	/**
	 * This function is used to get labels
	 */
	public function getLabels() {
		return $this->labels;
	}
	/**
	 * This function is used to set last Modifying User
	 *
	 * @param Google_Service_Drive_User $lastModifyingUser .
	 */
	public function setLastModifyingUser( Google_Service_Drive_User $lastModifyingUser ) { // @codingStandardsIgnoreLine
		$this->lastModifyingUser = $lastModifyingUser; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get  last Modifying User
	 */
	public function getLastModifyingUser() {
		return $this->lastModifyingUser; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set last Modifying User Name
	 *
	 * @param string $lastModifyingUserName .
	 */
	public function setLastModifyingUserName( $lastModifyingUserName ) { // @codingStandardsIgnoreLine
		$this->lastModifyingUserName = $lastModifyingUserName; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get last Modifying User Name
	 */
	public function getLastModifyingUserName() {
		return $this->lastModifyingUserName; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set last Viewed By Me Date
	 *
	 * @param string $lastViewedByMeDate .
	 */
	public function setLastViewedByMeDate( $lastViewedByMeDate ) { // @codingStandardsIgnoreLine
		$this->lastViewedByMeDate = $lastViewedByMeDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get last Viewed By Me Date
	 */
	public function getLastViewedByMeDate() {
		return $this->lastViewedByMeDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set marked Viewed By Me Date
	 *
	 * @param string $markedViewedByMeDate .
	 */
	public function setMarkedViewedByMeDate( $markedViewedByMeDate ) { // @codingStandardsIgnoreLine
		$this->markedViewedByMeDate = $markedViewedByMeDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set  marked Viewed By Me Date
	 */
	public function getMarkedViewedByMeDate() {
		return $this->markedViewedByMeDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set md5 Checksum
	 *
	 * @param string $md5Checksum .
	 */
	public function setMd5Checksum( $md5Checksum ) { // @codingStandardsIgnoreLine
		$this->md5Checksum = $md5Checksum; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get md5 Checksum
	 */
	public function getMd5Checksum() {
		return $this->md5Checksum; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set mime Type
	 *
	 * @param string $mimeType .
	 */
	public function setMimeType( $mimeType ) { // @codingStandardsIgnoreLine
		$this->mimeType = $mimeType; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set  mime Type
	 */
	public function getMimeType() {
		return $this->mimeType; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set modified By Me Date
	 *
	 * @param string $modifiedByMeDate .
	 */
	public function setModifiedByMeDate( $modifiedByMeDate ) { // @codingStandardsIgnoreLine
		$this->modifiedByMeDate = $modifiedByMeDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get modified By Me Date
	 */
	public function getModifiedByMeDate() {
		return $this->modifiedByMeDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set modified Date
	 *
	 * @param string $modifiedDate .
	 */
	public function setModifiedDate( $modifiedDate ) {//@codingStandardsIgnoreLine
		$this->modifiedDate = $modifiedDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get modified Date
	 */
	public function getModifiedDate() {
		return $this->modifiedDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set open With Links
	 *
	 * @param string $openWithLinks .
	 */
	public function setOpenWithLinks( $openWithLinks ) { //@codingStandardsIgnoreLine
		$this->openWithLinks = $openWithLinks; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get  open With Links
	 */
	public function getOpenWithLinks() {
		return $this->openWithLinks; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set original File name
	 *
	 * @param string $originalFilename .
	 */
	public function setOriginalFilename( $originalFilename ) { //@codingStandardsIgnoreLine
		$this->originalFilename = $originalFilename; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get original File name
	 */
	public function getOriginalFilename() {
		return $this->originalFilename; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set owner Names
	 *
	 * @param string $ownerNames .
	 */
	public function setOwnerNames( $ownerNames ) {// @codingStandardsIgnoreLine
		$this->ownerNames = $ownerNames; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get owner Names
	 */
	public function getOwnerNames() {
		return $this->ownerNames; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set owners
	 *
	 * @param string $owners .
	 */
	public function setOwners( $owners ) {
		$this->owners = $owners;
	}
	/**
	 * This function is used to get owners
	 */
	public function getOwners() {
		return $this->owners;
	}
	/**
	 * This function is used to set parents
	 *
	 * @param string $parents .
	 */
	public function setParents( $parents ) {
		$this->parents = $parents;
	}
	/**
	 * This function is used to set parents
	 */
	public function getParents() {
		return $this->parents;
	}
	/**
	 * This function is used to set permissions
	 *
	 * @param string $permissions .
	 */
	public function setPermissions( $permissions ) {
		$this->permissions = $permissions;
	}
	/**
	 * This function is used to set permissions
	 */
	public function getPermissions() {
		return $this->permissions;
	}
	/**
	 * This function is used to set properties
	 *
	 * @param string $properties .
	 */
	public function setProperties( $properties ) {
		$this->properties = $properties;
	}
	/**
	 * This function is used to set properties.
	 */
	public function getProperties() {
		return $this->properties;
	}
	/**
	 * This function is used to set quota byte used
	 *
	 * @param string $quotaBytesUsed .
	 */
	public function setQuotaBytesUsed( $quotaBytesUsed ) { // @codingStandardsIgnoreLine.
		$this->quotaBytesUsed = $quotaBytesUsed; // @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to  get quota byte used
	 */
	public function getQuotaBytesUsed() {
		return $this->quotaBytesUsed; // @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set shared
	 *
	 * @param string $shared .
	 */
	public function setShared( $shared ) {
		$this->shared = $shared;
	}
	/**
	 * This function is used to get shared
	 */
	public function getShared() {
		return $this->shared;
	}
	/**
	 * This function is used to set shared WithMe Date
	 *
	 * @param string $sharedWithMeDate .
	 */
	public function setSharedWithMeDate( $sharedWithMeDate ) { // @codingStandardsIgnoreLine
		$this->sharedWithMeDate = $sharedWithMeDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get  shared WithMe Dat
	 */
	public function getSharedWithMeDate() {
		return $this->sharedWithMeDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set sharing User
	 *
	 * @param Google_Service_Drive_User $sharingUser .
	 */
	public function setSharingUser( Google_Service_Drive_User $sharingUser ) { // @codingStandardsIgnoreLine
		$this->sharingUser = $sharingUser; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get sharing User
	 */
	public function getSharingUser() {
		return $this->sharingUser; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set thumbnail
	 *
	 * @param Google_Service_Drive_DriveFileThumbnail $thumbnail .
	 */
	public function setThumbnail( Google_Service_Drive_DriveFileThumbnail $thumbnail ) {
		$this->thumbnail = $thumbnail;
	}
	/**
	 * This function is used to get thumbnail
	 */
	public function getThumbnail() {
		return $this->thumbnail;
	}
	/**
	 * This function is used to set thumbnail Link
	 *
	 * @param string $thumbnailLink .
	 */
	public function setThumbnailLink( $thumbnailLink ) { // @codingStandardsIgnoreLine
		$this->thumbnailLink = $thumbnailLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get thumbnail Link
	 */
	public function getThumbnailLink() {
		return $this->thumbnailLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set title
	 *
	 * @param string $title .
	 */
	public function setTitle( $title ) {
		$this->title = $title;
	}
	/**
	 * This function is used to set title
	 */
	public function getTitle() {
		return $this->title;
	}
	/**
	 * This function is used to set user permission
	 *
	 * @param Google_Service_Drive_Permission $userPermission .
	 */
	public function setUserPermission( Google_Service_Drive_Permission $userPermission ) {//@codingStandardsIgnoreLine
		$this->userPermission = $userPermission; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get user permission
	 */
	public function getUserPermission() {
		return $this->userPermission; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set version
	 *
	 * @param string $version .
	 */
	public function setVersion( $version ) {
		$this->version = $version;
	}
	/**
	 * This function is used to get version
	 */
	public function getVersion() {
		return $this->version;
	}
	/**
	 * This function is used to set video Media Meta data
	 *
	 * @param Google_Service_Drive_DriveFileVideoMediaMetadata $videoMediaMetadata .
	 */
	public function setVideoMediaMetadata( Google_Service_Drive_DriveFileVideoMediaMetadata $videoMediaMetadata ) { // @codingStandardsIgnoreLine
		$this->videoMediaMetadata = $videoMediaMetadata; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get video Media Meta data
	 */
	public function getVideoMediaMetadata() {
		return $this->videoMediaMetadata; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set web Content Link
	 *
	 * @param string $webContentLink .
	 */
	public function setWebContentLink( $webContentLink ) { // @codingStandardsIgnoreLine
		$this->webContentLink = $webContentLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get web Content Link
	 */
	public function getWebContentLink() {
		return $this->webContentLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set web View Link
	 *
	 * @param string $webViewLink .
	 */
	public function setWebViewLink( $webViewLink ) { // @codingStandardsIgnoreLine
		$this->webViewLink = $webViewLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get web View Link
	 */
	public function getWebViewLink() {
		return $this->webViewLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set  writers Can Share
	 *
	 * @param string $writersCanShare .
	 */
	public function setWritersCanShare( $writersCanShare ) { // @codingStandardsIgnoreLine
		$this->writersCanShare = $writersCanShare; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get  writers Can Share
	 */
	public function getWritersCanShare() {
		return $this->writersCanShare; // @codingStandardsIgnoreLine
	}
}
/**
 * This class is used for drive file export links
 */
class Google_Service_Drive_DriveFileExportLinks extends Google_Model { // @codingStandardsIgnoreLine

}
/**
 * This class is used for file image media meta data
 */
class Google_Service_Drive_DriveFileImageMediaMetadata extends Google_Model { //@codingStandardsIgnoreLine
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for  aperture
	 *
	 * @var $aperture .
	 */
	public $aperture;
	/**
	 * Variable for cameraMake
	 *
	 * @var $cameraMake .
	 */
	public $cameraMake; // @codingStandardsIgnoreLine
	/**
	 * Variable for  camera Model
	 *
	 * @var $cameraModel .
	 */
	public $cameraModel; // @codingStandardsIgnoreLine
	/**
	 * Variable for color Space
	 *
	 * @var $colorSpace .
	 */
	public $colorSpace; // @codingStandardsIgnoreLine
	/**
	 * Variable for date
	 *
	 * @var $date .
	 */
	public $date;
	/**
	 * Variable for exposure Bias
	 *
	 * @var $exposureBias .
	 */
	public $exposureBias; // @codingStandardsIgnoreLine
	/**
	 * Variable for exposure Mode
	 *
	 * @var $exposureMode .
	 */
	public $exposureMode; // @codingStandardsIgnoreLine
	/**
	 * Variable for exposure Time
	 *
	 * @var $exposureTime .
	 */
	public $exposureTime; // @codingStandardsIgnoreLine
	/**
	 * Variable for flash Used
	 *
	 * @var $flashUsed .
	 */
	public $flashUsed; // @codingStandardsIgnoreLine
	/**
	 * Variable for focal Length
	 *
	 * @var $focalLength .
	 */
	public $focalLength; // @codingStandardsIgnoreLine
	/**
	 * Variable for height
	 *
	 * @var $height .
	 */
	public $height;
	/**
	 * Variable for iso Speed
	 *
	 * @var $isoSpeed .
	 */
	public $isoSpeed; // @codingStandardsIgnoreLine
	/**
	 * Variable for lens
	 *
	 * @var $lens .
	 */
	public $lens;
	/**
	 * Variable for location Type
	 *
	 * @var $locationType .
	 */
	protected $locationType = 'Google_Service_Drive_DriveFileImageMediaMetadataLocation'; // @codingStandardsIgnoreLine
	/**
	 * Variable for location Data Type
	 *
	 * @var $locationDataType .
	 */
	protected $locationDataType = ''; // @codingStandardsIgnoreLine
	/**
	 * Variable for max Aperture Value
	 *
	 * @var $maxApertureValue .
	 */
	public $maxApertureValue; // @codingStandardsIgnoreLine
	/**
	 * Variable for metering Mode
	 *
	 * @var $meteringMode .
	 */
	public $meteringMode; // @codingStandardsIgnoreLine
	/**
	 * Variable for rotation
	 *
	 * @var $rotation .
	 */
	public $rotation;
	/**
	 * Variable for sensor
	 *
	 * @var $sensor .
	 */
	public $sensor;
	/**
	 * Variable for subject Distance
	 *
	 * @var $subjectDistance .
	 */
	public $subjectDistance; // @codingStandardsIgnoreLine
	/**
	 * Variable for white Balance
	 *
	 * @var $whiteBalance .
	 */
	public $whiteBalance; // @codingStandardsIgnoreLine
	/**
	 * Variable for width
	 *
	 * @var $width .
	 */
	public $width;
	/**
	 * This function is used to set aperture
	 *
	 * @param string $aperture .
	 */
	public function setAperture( $aperture ) {
		$this->aperture = $aperture;
	}
	/**
	 * This function is used to get aperture
	 */
	public function getAperture() {
		return $this->aperture;
	}
	/**
	 * This function is used to set camera Make
	 *
	 * @param string $cameraMake .
	 */
	public function setCameraMake( $cameraMake ) { // @codingStandardsIgnoreLine
		$this->cameraMake = $cameraMake; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get camera Make
	 */
	public function getCameraMake() {
		return $this->cameraMake; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set camera Model
	 *
	 * @param string $cameraModel .
	 */
	public function setCameraModel( $cameraModel ) { // @codingStandardsIgnoreLine
		$this->cameraModel = $cameraModel; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get camera Model
	 */
	public function getCameraModel() {
		return $this->cameraModel; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set color Space
	 *
	 * @param string $colorSpace .
	 */
	public function setColorSpace( $colorSpace ) { // @codingStandardsIgnoreLine
		$this->colorSpace = $colorSpace; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get color Space
	 */
	public function getColorSpace() {
		return $this->colorSpace; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set date
	 *
	 * @param string $date .
	 */
	public function setDate( $date ) {
		$this->date = $date;
	}
	/**
	 * This function is used to get date.
	 */
	public function getDate() {
		return $this->date;
	}
	/**
	 * This function is used to set exposure Bias
	 *
	 * @param string $exposureBias .
	 */
	public function setExposureBias( $exposureBias ) { // @codingStandardsIgnoreLine
		$this->exposureBias = $exposureBias; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get exposure Bias
	 */
	public function getExposureBias() {
		return $this->exposureBias; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set exposure Mode
	 *
	 * @param string $exposureMode .
	 */
	public function setExposureMode( $exposureMode ) { // @codingStandardsIgnoreLine
		$this->exposureMode = $exposureMode; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get exposure Mode
	 */
	public function getExposureMode() {
		return $this->exposureMode; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set exposure Time
	 *
	 * @param string $exposureTime .
	 */
	public function setExposureTime( $exposureTime ) { // @codingStandardsIgnoreLine
		$this->exposureTime = $exposureTime; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get exposure Time
	 */
	public function getExposureTime() {
		return $this->exposureTime; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set flash Used
	 *
	 * @param string $flashUsed .
	 */
	public function setFlashUsed( $flashUsed ) { // @codingStandardsIgnoreLine
		$this->flashUsed = $flashUsed; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set flash Used
	 */
	public function getFlashUsed() {
		return $this->flashUsed; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set focal Length
	 *
	 * @param string $focalLength .
	 */
	public function setFocalLength( $focalLength ) { // @codingStandardsIgnoreLine
		$this->focalLength = $focalLength; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get focal Length
	 */
	public function getFocalLength() {
		return $this->focalLength; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set height
	 *
	 * @param int $height .
	 */
	public function setHeight( $height ) {
		$this->height = $height;
	}
	/**
	 * This function is used to get height.
	 */
	public function getHeight() {
		return $this->height;
	}
	/**
	 * This function is used to set iso Speed
	 *
	 * @param string $isoSpeed .
	 */
	public function setIsoSpeed( $isoSpeed ) { // @codingStandardsIgnoreLine
		$this->isoSpeed = $isoSpeed; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get iso Speed
	 */
	public function getIsoSpeed() {
		return $this->isoSpeed; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set lens
	 *
	 * @param string $lens .
	 */
	public function setLens( $lens ) {
		$this->lens = $lens;
	}
	/**
	 * This function is used to get lens.
	 */
	public function getLens() {
		return $this->lens;
	}
	/**
	 * This function is used to set location
	 *
	 * @param Google_Service_Drive_DriveFileImageMediaMetadataLocation $location .
	 */
	public function setLocation( Google_Service_Drive_DriveFileImageMediaMetadataLocation $location ) {
		$this->location = $location;
	}
	/**
	 * This function is used to get location.
	 */
	public function getLocation() {
		return $this->location;
	}
	/**
	 * This function is used to set max Aperture Value
	 *
	 * @param string $maxApertureValue .
	 */
	public function setMaxApertureValue( $maxApertureValue ) { // @codingStandardsIgnoreLine
		$this->maxApertureValue = $maxApertureValue; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get max Aperture Value
	 */
	public function getMaxApertureValue() {
		return $this->maxApertureValue; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set metering Mode
	 *
	 * @param string $meteringMode .
	 */
	public function setMeteringMode( $meteringMode ) { // @codingStandardsIgnoreLine
		$this->meteringMode = $meteringMode; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get metering Mode
	 */
	public function getMeteringMode() {
		return $this->meteringMode; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set rotation
	 *
	 * @param string $rotation .
	 */
	public function setRotation( $rotation ) {
		$this->rotation = $rotation;
	}
	/**
	 * This function is used to get rotation
	 */
	public function getRotation() {
		return $this->rotation;
	}
	/**
	 * This function is used to set sensor
	 *
	 * @param string $sensor .
	 */
	public function setSensor( $sensor ) {
		$this->sensor = $sensor;
	}
	/**
	 * This function is used to get sensor
	 */
	public function getSensor() {
		return $this->sensor;
	}
	/**
	 * This function is used to set subject Distance
	 *
	 * @param string $subjectDistance .
	 */
	public function setSubjectDistance( $subjectDistance ) { // @codingStandardsIgnoreLine
		$this->subjectDistance = $subjectDistance; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get subject Distance.
	 */
	public function getSubjectDistance() {
		return $this->subjectDistance; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set white Balance
	 *
	 * @param string $whiteBalance .
	 */
	public function setWhiteBalance( $whiteBalance ) { // @codingStandardsIgnoreLine
		$this->whiteBalance = $whiteBalance; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get white Balance.
	 */
	public function getWhiteBalance() {
		return $this->whiteBalance; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set width
	 *
	 * @param int $width .
	 */
	public function setWidth( $width ) {
		$this->width = $width;
	}
	/**
	 * This function is used to set width
	 */
	public function getWidth() {
		return $this->width;
	}
}
/**
 * This class is used for media mata data location
 */
class Google_Service_Drive_DriveFileImageMediaMetadataLocation extends Google_Model { // @codingStandardsIgnoreLine
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for altitude
	 *
	 * @var $altitude .
	 */
	public $altitude;
	/**
	 * Variable for latitude
	 *
	 * @var $latitude .
	 */
	public $latitude;
	/**
	 * Variable for longitude
	 *
	 * @var $longitude .
	 */
	public $longitude;
	/**
	 * This function is used to set altitude
	 *
	 * @param string $altitude .
	 */
	public function setAltitude( $altitude ) {
		$this->altitude = $altitude;
	}
	/**
	 * This function is used to get altitude
	 */
	public function getAltitude() {
		return $this->altitude;
	}
	/**
	 * This function is used to set latitude
	 *
	 * @param string $latitude .
	 */
	public function setLatitude( $latitude ) {
		$this->latitude = $latitude;
	}
	/**
	 * This function is used to get latitude
	 */
	public function getLatitude() {
		return $this->latitude;
	}
	/**
	 * This function is used to set longitude
	 *
	 * @param string $longitude .
	 */
	public function setLongitude( $longitude ) {
		$this->longitude = $longitude;
	}
	/**
	 * This function is used to get longitude
	 */
	public function getLongitude() {
		return $this->longitude;
	}
}
/**
 * This class is used for indexable text file
 */
class Google_Service_Drive_DriveFileIndexableText extends Google_Model { // @codingStandardsIgnoreLine
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for text
	 *
	 * @var $text .
	 */
	public $text;
	/**
	 * This function is used to set text
	 *
	 * @param string $text .
	 */
	public function setText( $text ) {
		$this->text = $text;
	}
	/**
	 * This function is used to get text
	 */
	public function getText() {
		return $this->text;
	}
}
/**
 * This class is used for drive file labels
 */
class Google_Service_Drive_DriveFileLabels extends Google_Model { // @codingStandardsIgnoreLine
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for hidden
	 *
	 * @var $hidden .
	 */
	public $hidden;
	/**
	 * Variable for restricted
	 *
	 * @var $restricted .
	 */
	public $restricted;
	/**
	 * Variable for starred
	 *
	 * @var $starred .
	 */
	public $starred;
	/**
	 * Variable for  trashed
	 *
	 * @var $trashed .
	 */
	public $trashed;
	/**
	 * Variable for viewed
	 *
	 * @var $viewed .
	 */
	public $viewed;
	/**
	 * This function is used to set hidden
	 *
	 * @param string $hidden .
	 */
	public function setHidden( $hidden ) {
		$this->hidden = $hidden;
	}
	/**
	 * This function is used to get hidden
	 */
	public function getHidden() {
		return $this->hidden;
	}
	/**
	 * This function is used to set restricted
	 *
	 * @param string $restricted .
	 */
	public function setRestricted( $restricted ) {
		$this->restricted = $restricted;
	}
	/**
	 * This function is used to get restricted
	 */
	public function getRestricted() {
		return $this->restricted;
	}
	/**
	 * This function is used to set starred
	 *
	 * @param string $starred .
	 */
	public function setStarred( $starred ) {
		$this->starred = $starred;
	}
	/**
	 * This function is used to get starred
	 */
	public function getStarred() {
		return $this->starred;
	}
	/**
	 * This function is used to set trashed
	 *
	 * @param string $trashed .
	 */
	public function setTrashed( $trashed ) {
		$this->trashed = $trashed;
	}
	/**
	 * This function is used to get trashed
	 */
	public function getTrashed() {
		return $this->trashed;
	}
	/**
	 * This function is used to set  viewed
	 *
	 * @param string $viewed .
	 */
	public function setViewed( $viewed ) {
		$this->viewed = $viewed;
	}
	/**
	 * This function is used to set viewed
	 */
	public function getViewed() {
		return $this->viewed;
	}
}
/**
 * This class is used for file open with links
 */
class Google_Service_Drive_DriveFileOpenWithLinks extends Google_Model { // @codingStandardsIgnoreLine

}
/**
 * This class is used for thumbnail file
 */
class Google_Service_Drive_DriveFileThumbnail extends Google_Model { //@codingStandardsIgnoreLine
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for image
	 *
	 * @var $image .
	 */
	public $image;
	/**
	 * Variable for mime Type
	 *
	 * @var $mimeType .
	 */
	public $mimeType; //@codingStandardsIgnoreLine
	/**
	 * This function is used to set image
	 *
	 * @param string $image .
	 */
	public function setImage( $image ) {
		$this->image = $image;
	}
	/**
	 * This function is used to get image
	 */
	public function getImage() {
		return $this->image;
	}
	/**
	 * This function is used to set mime Type
	 *
	 * @param string $mimeType .
	 */
	public function setMimeType( $mimeType ) { //@codingStandardsIgnoreLine
		$this->mimeType = $mimeType; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set  mime Type
	 */
	public function getMimeType() {
		return $this->mimeType; // @codingStandardsIgnoreLine
	}
}
/**
 * This class is used for video media matedata file
 */
class Google_Service_Drive_DriveFileVideoMediaMetadata extends Google_Model { //@codingStandardsIgnoreLine
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for duration Millis
	 *
	 * @var $durationMillis .
	 */
	public $durationMillis; //@codingStandardsIgnoreLine
	/**
	 * Variable for height
	 *
	 * @var $height .
	 */
	public $height;
	/**
	 * Variable for width
	 *
	 * @var $width .
	 */
	public $width;
	/**
	 * This function is used to set duration Millis
	 *
	 * @param int $durationMillis .
	 */
	public function setDurationMillis( $durationMillis ) {// @codingStandardsIgnoreLine
		$this->durationMillis = $durationMillis; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get duration Millis
	 */
	public function getDurationMillis() {
		return $this->durationMillis; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set height
	 *
	 * @param int $height .
	 */
	public function setHeight( $height ) {
		$this->height = $height;
	}
	/**
	 * This function is used to get height
	 */
	public function getHeight() {
		return $this->height;
	}
	/**
	 * This function is used to set width
	 *
	 * @param int $width .
	 */
	public function setWidth( $width ) {
		$this->width = $width;
	}
	/**
	 * This function is used to get width
	 */
	public function getWidth() {
		return $this->width;
	}
}
/**
 * This class is used for file list
 */
class Google_Service_Drive_FileList extends Google_Collection { // @codingStandardsIgnoreLine
	/**
	 * Collection of key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'items';
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * For variable etag
	 *
	 * @var $etag .
	 */
	public $etag;
	/**
	 * Variable for items Type
	 *
	 * @var $itemsType .
	 */
	protected $itemsType = 'Google_Service_Drive_DriveFile'; // @codingStandardsIgnoreLine
	/**
	 * Variable for items Data Type
	 *
	 * @var $itemsDataType .
	 */
	protected $itemsDataType = 'array'; //@codingStandardsIgnoreLine
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Next link variable with access of public
	 *
	 * @var $nextLink .
	 */
	public $nextLink; //@codingStandardsIgnoreLine
	/**
	 * Variable for next page token with access of public
	 *
	 * @var $nextPageToken .
	 */
	public $nextPageToken; //@codingStandardsIgnoreLine
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink;// @codingStandardsIgnoreLine
	/**
	 * This function is used to set etag
	 *
	 * @param string $etag .
	 */
	public function setEtag( $etag ) {
		$this->etag = $etag;
	}
	/**
	 * This function is used to get etag
	 */
	public function getEtag() {
		return $this->etag;
	}
	/**
	 * This function is used to set items
	 *
	 * @param string $items .
	 */
	public function setItems( $items ) {
		$this->items = $items;
	}
	/**
	 * This function is used to get items
	 */
	public function getItems() {
		return $this->items;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set next link
	 *
	 * @param string $nextLink .
	 */
	public function setNextLink( $nextLink ) { //@codingStandardsIgnoreLine
		$this->nextLink = $nextLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get next link
	 */
	public function getNextLink() {
		return $this->nextLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set next page token
	 *
	 * @param string $nextPageToken .
	 */
	public function setNextPageToken( $nextPageToken ) { //@codingStandardsIgnoreLine
		$this->nextPageToken = $nextPageToken;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get next page token
	 */
	public function getNextPageToken() {
		return $this->nextPageToken;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used for parent list
 */
class Google_Service_Drive_ParentList extends Google_Collection { // @codingStandardsIgnoreLine
	/**
	 * Collection of key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'items';
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * For variable etag
	 *
	 * @var $etag .
	 */
	public $etag;
	/**
	 * Variable for items Type
	 *
	 * @var $itemsType .
	 */
	protected $itemsType = 'Google_Service_Drive_ParentReference'; // @codingStandardsIgnoreLine
	/**
	 * Variable for items Data Type
	 *
	 * @var $itemsDataType .
	 */
	protected $itemsDataType = 'array'; // @codingStandardsIgnoreLine
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink;// @codingStandardsIgnoreLine
	/**
	 * This function is used to set etag
	 *
	 * @param string $etag .
	 */
	public function setEtag( $etag ) {
		$this->etag = $etag;
	}
	/**
	 * This function is used to get etag
	 */
	public function getEtag() {
		return $this->etag;
	}
	/**
	 * This function is used to set items
	 *
	 * @param string $items .
	 */
	public function setItems( $items ) {
		$this->items = $items;
	}
	/**
	 * This function is used to get items
	 */
	public function getItems() {
		return $this->items;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used for parent reference
 */
class Google_Service_Drive_ParentReference extends Google_Model { //@codingStandardsIgnoreLine
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for id
	 *
	 * @var $id .
	 */
	public $id;
	/**
	 * Variable for  isRoot
	 *
	 * @var $isRoot .
	 */
	public $isRoot; //@codingStandardsIgnoreLine
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Variable for parent Link
	 *
	 * @var $parentLink .
	 */
	public $parentLink; //@codingStandardsIgnoreLine
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink;// @codingStandardsIgnoreLine
	/**
	 * Function for set id.
	 *
	 * @param int $id .
	 */
	public function setId( $id ) {
		$this->id = $id;
	}
	/**
	 * Function for get id.
	 */
	public function getId() {
		return $this->id;
	}
	/**
	 * Function for set  isRoot.
	 *
	 * @param string $isRoot .
	 */
	public function setIsRoot( $isRoot ) { // @codingStandardsIgnoreLine
		$this->isRoot = $isRoot; // @codingStandardsIgnoreLine
	}
	/**
	 * Function for get isRoot
	 */
	public function getIsRoot() {
		return $this->isRoot; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set parent Link
	 *
	 * @param string $parentLink .
	 */
	public function setParentLink( $parentLink ) {// @codingStandardsIgnoreLine.
		$this->parentLink = $parentLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get parent Link
	 */
	public function getParentLink() {
		return $this->parentLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used for permission
 */
class Google_Service_Drive_Permission extends Google_Collection { // @codingStandardsIgnoreLine
	/**
	 * Collection of key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'additionalRoles';
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * For additional roles with public access
	 *
	 * @var $additionalRoles .
	 */
	public $additionalRoles; //@codingStandardsIgnoreLine
	/**
	 * For  auth Key with public access
	 *
	 * @var $authKey .
	 */
	public $authKey; //@codingStandardsIgnoreLine
	/**
	 * For domain with public access
	 *
	 * @var $domain .
	 */
	public $domain;
	/**
	 * For email Address with public access
	 *
	 * @var $emailAddress .
	 */
	public $emailAddress; //@codingStandardsIgnoreLine
	/**
	 * For variable etag
	 *
	 * @var $etag .
	 */
	public $etag;
	/**
	 * Variable for id
	 *
	 * @var $id .
	 */
	public $id;
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Nmae variable with access public .
	 *
	 * @var $name .
	 */
	public $name;
	/**
	 * Photo Link variable with access public .
	 *
	 * @var $photoLink .
	 */
	public $photoLink; //@codingStandardsIgnoreLine
	/**
	 * Role variable with access public .
	 *
	 * @var $role .
	 */
	public $role;

	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink;// @codingStandardsIgnoreLine
	/**
	 * Use for type
	 *
	 * @var $type .
	 */
	public $type;
	/**
	 * Variable use for value
	 *
	 * @var $value .
	 */
	public $value;
	/**
	 * Variable use for with Link
	 *
	 * @var $withLink .
	 */
	public $withLink; //@codingStandardsIgnoreLine
	/**
	 * This function is used to set additional roles
	 *
	 * @param string $additionalRoles .
	 */
	public function setAdditionalRoles( $additionalRoles ) {// @codingStandardsIgnoreLine
		$this->additionalRoles = $additionalRoles; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get additional roles
	 */
	public function getAdditionalRoles() {
		return $this->additionalRoles; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set auth Key
	 *
	 * @param string $authKey .
	 */
	public function setAuthKey( $authKey ) {// @codingStandardsIgnoreLine
		$this->authKey = $authKey; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get authKey
	 */
	public function getAuthKey() {
		return $this->authKey; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get domain
	 *
	 * @param string $domain .
	 */
	public function setDomain( $domain ) {
		$this->domain = $domain;
	}
	/**
	 * This function is used to get domain
	 */
	public function getDomain() {
		return $this->domain;
	}
	/**
	 * This function is used to set email Address
	 *
	 * @param string $emailAddress .
	 */
	public function setEmailAddress( $emailAddress ) {// @codingStandardsIgnoreLine
		$this->emailAddress = $emailAddress; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get emailAddress
	 */
	public function getEmailAddress() {
		return $this->emailAddress; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set etag
	 *
	 * @param string $etag .
	 */
	public function setEtag( $etag ) {
		$this->etag = $etag;
	}
	/**
	 * This function is used to get etag
	 */
	public function getEtag() {
		return $this->etag;
	}
	/**
	 * Function for set id.
	 *
	 * @param int $id .
	 */
	public function setId( $id ) {
		$this->id = $id;
	}
	/**
	 * Function for get id.
	 */
	public function getId() {
		return $this->id;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set name
	 *
	 * @param string $name .
	 */
	public function setName( $name ) {
		$this->name = $name;
	}
	/**
	 * This function is used to get name
	 */
	public function getName() {
		return $this->name;
	}
	/**
	 * This function is used to set photo Link
	 *
	 * @param string $photoLink .
	 */
	public function setPhotoLink( $photoLink ) { // @codingStandardsIgnoreLine
		$this->photoLink = $photoLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get photo Link
	 */
	public function getPhotoLink() {
		return $this->photoLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set role
	 *
	 * @param string $role .
	 */
	public function setRole( $role ) {
		$this->role = $role;
	}
	/**
	 * This function is used to get role
	 */
	public function getRole() {
		return $this->role;
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set type
	 *
	 * @param string $type .
	 */
	public function setType( $type ) {
		$this->type = $type;
	}
	/**
	 * This function is used to get type
	 */
	public function getType() {
		return $this->type;
	}
	/**
	 * This function is used to set value
	 *
	 * @param string $value .
	 */
	public function setValue( $value ) {
		$this->value = $value;
	}
	/**
	 * This function is used to get value
	 */
	public function getValue() {
		return $this->value;
	}
	/**
	 * This function is used to set with Link
	 *
	 * @param string $withLink .
	 */
	public function setWithLink( $withLink ) { // @codingStandardsIgnoreLine
		$this->withLink = $withLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get with Link
	 */
	public function getWithLink() {
		return $this->withLink; // @codingStandardsIgnoreLine
	}
}
/**
 * This class is used for permission id
 */
class Google_Service_Drive_PermissionId extends Google_Model { //@codingStandardsIgnoreLine
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for id
	 *
	 * @var $id .
	 */
	public $id;
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Function for set id.
	 *
	 * @param int $id .
	 */
	public function setId( $id ) {
		$this->id = $id;
	}
	/**
	 * Function for get id.
	 */
	public function getId() {
		return $this->id;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
}
/**
 * This class is used for permission list
 */
class Google_Service_Drive_PermissionList extends Google_Collection { //@codingStandardsIgnoreLine
	/**
	 * Collection of key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'items';
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * For variable etag
	 *
	 * @var $etag .
	 */
	public $etag;
	/**
	 * Variable for items Type
	 *
	 * @var $itemsType .
	 */
	protected $itemsType = 'Google_Service_Drive_Permission'; // @codingStandardsIgnoreLine
	/**
	 * Variable for items Data Type
	 *
	 * @var $itemsDataType .
	 */
	protected $itemsDataType = 'array'; // @codingStandardsIgnoreLine
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink;// @codingStandardsIgnoreLine
	/**
	 * This function is used to set etag
	 *
	 * @param string $etag .
	 */
	public function setEtag( $etag ) {
		$this->etag = $etag;
	}
	/**
	 * This function is used to get etag
	 */
	public function getEtag() {
		return $this->etag;
	}
	/**
	 * This function is used to set items
	 *
	 * @param string $items .
	 */
	public function setItems( $items ) {
		$this->items = $items;
	}
	/**
	 * This function is used to get items
	 */
	public function getItems() {
		return $this->items;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used for property
 */
class Google_Service_Drive_Property extends Google_Model { //@codingStandardsIgnoreLine
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * For variable etag
	 *
	 * @var $etag .
	 */
	public $etag;
	/**
	 * For variable key
	 *
	 * @var $key .
	 */
	public $key;
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink;// @codingStandardsIgnoreLine
	/**
	 * Variable use for value
	 *
	 * @var $value .
	 */
	public $value;
	/**
	 * Variable use for visibility
	 *
	 * @var $visibility .
	 */
	public $visibility;
	/**
	 * This function is used to set etag
	 *
	 * @param string $etag .
	 */
	public function setEtag( $etag ) {
		$this->etag = $etag;
	}
	/**
	 * This function is used to get etag
	 */
	public function getEtag() {
		return $this->etag;
	}
	/**
	 * This function is used to set key
	 *
	 * @param string $key .
	 */
	public function setKey( $key ) {
		$this->key = $key;
	}
	/**
	 * This function is used to set key
	 */
	public function getKey() {
		return $this->key;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set value
	 *
	 * @param string $value .
	 */
	public function setValue( $value ) {
		$this->value = $value;
	}
	/**
	 * This function is used to get value
	 */
	public function getValue() {
		return $this->value;
	}
	/**
	 * This function is used to set visibility
	 *
	 * @param string $visibility .
	 */
	public function setVisibility( $visibility ) {
		$this->visibility = $visibility;
	}
	/**
	 * This function is used to get visibility
	 */
	public function getVisibility() {
		return $this->visibility;
	}
}
/**
 * This class is used for property list
 */
class Google_Service_Drive_PropertyList extends Google_Collection { //@codingStandardsIgnoreLine
	/**
	 * Collection of key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'items';
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * For variable etag
	 *
	 * @var $etag .
	 */
	public $etag;
	/**
	 * Variable for items Type
	 *
	 * @var $itemsType .
	 */
	protected $itemsType = 'Google_Service_Drive_Property'; // @codingStandardsIgnoreLine
	/**
	 * Variable for items Data Type
	 *
	 * @var $itemsDataType .
	 */
	protected $itemsDataType = 'array'; // @codingStandardsIgnoreLine
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink;// @codingStandardsIgnoreLine
	/**
	 * This function is used to set etag
	 *
	 * @param string $etag .
	 */
	public function setEtag( $etag ) {
		$this->etag = $etag;
	}
	/**
	 * This function is used to get etag
	 */
	public function getEtag() {
		return $this->etag;
	}
	/**
	 * This function is used to set items
	 *
	 * @param string $items .
	 */
	public function setItems( $items ) {
		$this->items = $items;
	}
	/**
	 * This function is used to get items
	 */
	public function getItems() {
		return $this->items;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used for revision
 */
class Google_Service_Drive_Revision extends Google_Model { //@codingStandardsIgnoreLine
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for download Url
	 *
	 * @var $downloadUrl .
	 */
	public $downloadUrl; //@codingStandardsIgnoreLine
	/**
	 * For variable etag
	 *
	 * @var $etag .
	 */
	public $etag;
	/**
	 * For variable export Links
	 *
	 * @var $exportLinks .
	 */
	public $exportLinks; //@codingStandardsIgnoreLine
	/**
	 * For variable file Size
	 *
	 * @var $fileSize .
	 */
	public $fileSize; //@codingStandardsIgnoreLine
	/**
	 * Variable for id
	 *
	 * @var $id .
	 */
	public $id;
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Variable for last Modifying User Type
	 *
	 * @var $lastModifyingUserType .
	 */
	protected $lastModifyingUserType = 'Google_Service_Drive_User'; //@codingStandardsIgnoreLine
	/**
	 * Variable for last Modifying User data Type
	 *
	 * @var $lastModifyingUserDataType .
	 */
	protected $lastModifyingUserDataType = ''; //@codingStandardsIgnoreLine
	/**
	 * Variable for last Modifying User Name
	 *
	 * @var $lastModifyingUserName .
	 */
	public $lastModifyingUserName; //@codingStandardsIgnoreLine
	/**
	 * Variable for md5 Checksum
	 *
	 * @var $md5Checksum .
	 */
	public $md5Checksum; //@codingStandardsIgnoreLine
	/**
	 * Variable for mime Type
	 *
	 * @var $mimeType .
	 */
	public $mimeType; //@codingStandardsIgnoreLine
	/**
	 * Variable for modified Date
	 *
	 * @var $modifiedDate .
	 */
	public $modifiedDate; //@codingStandardsIgnoreLine
	/**
	 * Variable for original File name
	 *
	 * @var $originalFilename .
	 */
	public $originalFilename; //@codingStandardsIgnoreLine
	/**
	 * Variable for pinned
	 *
	 * @var $pinned .
	 */
	public $pinned;
	/**
	 * Variable for publish Auto
	 *
	 * @var $publishAuto .
	 */
	public $publishAuto;//@codingStandardsIgnoreLine
	/**
	 * Variable for published
	 *
	 * @var $published .
	 */
	public $published;
	/**
	 * Variable for published Link
	 *
	 * @var $publishedLink .
	 */
	public $publishedLink;//@codingStandardsIgnoreLine
	/**
	 * Variable for published Outside Domain
	 *
	 * @var $publishedOutsideDomain .
	 */
	public $publishedOutsideDomain;//@codingStandardsIgnoreLine
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink;// @codingStandardsIgnoreLine
	/**
	 * Function is for  download Url
	 *
	 * @param string $downloadUrl .
	 */
	public function setDownloadUrl( $downloadUrl ) { //@codingStandardsIgnoreLine
		$this->downloadUrl = $downloadUrl; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for  download Url
	 */
	public function getDownloadUrl() {
		return $this->downloadUrl; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set etag
	 *
	 * @param string $etag .
	 */
	public function setEtag( $etag ) {
		$this->etag = $etag;
	}
	/**
	 * This function is used to get etag
	 */
	public function getEtag() {
		return $this->etag;
	}
	/**
	 * Function is for set export Links
	 *
	 * @param string $exportLinks .
	 */
	public function setExportLinks( $exportLinks ) { //@codingStandardsIgnoreLine
		$this->exportLinks = $exportLinks; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for get export Links
	 */
	public function getExportLinks() {
		return $this->exportLinks; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for set file Size
	 *
	 * @param string $fileSize .
	 */
	public function setFileSize( $fileSize ) { //@codingStandardsIgnoreLine
		$this->fileSize = $fileSize; // @codingStandardsIgnoreLine
	}
	/**
	 * Function is for get file Size
	 */
	public function getFileSize() {
		return $this->fileSize; // @codingStandardsIgnoreLine
	}
	/**
	 * Function for set id.
	 *
	 * @param int $id .
	 */
	public function setId( $id ) {
		$this->id = $id;
	}
	/**
	 * Function for get id.
	 */
	public function getId() {
		return $this->id;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set last Modifying User
	 *
	 * @param Google_Service_Drive_User $lastModifyingUser .
	 */
	public function setLastModifyingUser( Google_Service_Drive_User $lastModifyingUser ) { //@codingStandardsIgnoreLine
		$this->lastModifyingUser = $lastModifyingUser; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get  last Modifying User
	 */
	public function getLastModifyingUser() {
		return $this->lastModifyingUser; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set last Modifying User Name
	 *
	 * @param string $lastModifyingUserName .
	 */
	public function setLastModifyingUserName( $lastModifyingUserName ) { //@codingStandardsIgnoreLine
		$this->lastModifyingUserName = $lastModifyingUserName; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get last Modifying User Name
	 */
	public function getLastModifyingUserName() {
		return $this->lastModifyingUserName; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set md5 Checksum
	 *
	 * @param string $md5Checksum .
	 */
	public function setMd5Checksum( $md5Checksum ) { //@codingStandardsIgnoreLine
		$this->md5Checksum = $md5Checksum; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get md5 Checksum
	 */
	public function getMd5Checksum() {
		return $this->md5Checksum; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set mime Type
	 *
	 * @param string $mimeType .
	 */
	public function setMimeType( $mimeType ) { //@codingStandardsIgnoreLine
		$this->mimeType = $mimeType; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set  mime Type
	 */
	public function getMimeType() {
		return $this->mimeType; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set modified Date
	 *
	 * @param string $modifiedDate .
	 */
	public function setModifiedDate( $modifiedDate ) { //@codingStandardsIgnoreLine
		$this->modifiedDate = $modifiedDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get modified Date
	 */
	public function getModifiedDate() {
		return $this->modifiedDate; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set original File name
	 *
	 * @param string $originalFilename .
	 */
	public function setOriginalFilename( $originalFilename ) { //@codingStandardsIgnoreLine
		$this->originalFilename = $originalFilename; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get original File name
	 */
	public function getOriginalFilename() {
		return $this->originalFilename; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set pinned
	 *
	 * @param string $pinned .
	 */
	public function setPinned( $pinned ) {
		$this->pinned = $pinned;
	}
	/**
	 * This function is used to get pinned
	 */
	public function getPinned() {
		return $this->pinned;
	}
	/**
	 * This function is used to set publish Auto
	 *
	 * @param string $publishAuto .
	 */
	public function setPublishAuto( $publishAuto ) {// @codingStandardsIgnoreLine.
		$this->publishAuto = $publishAuto; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get publish Auto
	 */
	public function getPublishAuto() {
		return $this->publishAuto; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set published
	 *
	 * @param string $published .
	 */
	public function setPublished( $published ) {
		$this->published = $published;
	}
	/**
	 * This function is used to get published
	 */
	public function getPublished() {
		return $this->published;
	}
	/**
	 * This function is used to set published Link
	 *
	 * @param string $publishedLink .
	 */
	public function setPublishedLink( $publishedLink ) {// @codingStandardsIgnoreLine.
		$this->publishedLink = $publishedLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get publishedLink.
	 */
	public function getPublishedLink() {
		return $this->publishedLink; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set published Outside Domain
	 *
	 * @param string $publishedOutsideDomain .
	 */
	public function setPublishedOutsideDomain( $publishedOutsideDomain ) {// @codingStandardsIgnoreLine.
		$this->publishedOutsideDomain = $publishedOutsideDomain;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get published Outside Domain
	 */
	public function getPublishedOutsideDomain() {
		return $this->publishedOutsideDomain;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used for revision export links
 */
class Google_Service_Drive_RevisionExportLinks extends Google_Model { //@codingStandardsIgnoreLine

}
/**
 * This class is used for revision list
 */
class Google_Service_Drive_RevisionList extends Google_Collection { //@codingStandardsIgnoreLine
	/**
	 * Collection of key
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'items';
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * For variable etag
	 *
	 * @var $etag .
	 */
	public $etag;
	/**
	 * Variable for items Type
	 *
	 * @var $itemsType .
	 */
	protected $itemsType = 'Google_Service_Drive_Revision'; // @codingStandardsIgnoreLine
	/**
	 * Variable for items Data Type
	 *
	 * @var $itemsDataType .
	 */
	protected $itemsDataType = 'array'; // @codingStandardsIgnoreLine
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Self link variable with public access
	 *
	 * @var $selfLink .
	 */
	public $selfLink;// @codingStandardsIgnoreLine
	/**
	 * This function is used to set etag
	 *
	 * @param string $etag .
	 */
	public function setEtag( $etag ) {
		$this->etag = $etag;
	}
	/**
	 * This function is used to get etag
	 */
	public function getEtag() {
		return $this->etag;
	}
	/**
	 * This function is used to set items
	 *
	 * @param string $items .
	 */
	public function setItems( $items ) {
		$this->items = $items;
	}
	/**
	 * This function is used to get items
	 */
	public function getItems() {
		return $this->items;
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set self link
	 *
	 * @param string $selfLink .
	 */
	public function setSelfLink( $selfLink ) {// @codingStandardsIgnoreLine.
		$this->selfLink = $selfLink;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get self link
	 */
	public function getSelfLink() {
		return $this->selfLink;// @codingStandardsIgnoreLine.
	}
}
/**
 * This class is used for user
 */
class Google_Service_Drive_User extends Google_Model { //@codingStandardsIgnoreLine
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for display Name
	 *
	 * @var $displayName .
	 */
	public $displayName;// @codingStandardsIgnoreLine
	/**
	 * Variable for email Address
	 *
	 * @var $emailAddress .
	 */
	public $emailAddress;// @codingStandardsIgnoreLine
	/**
	 * Variable for isAuthenticated User
	 *
	 * @var $isAuthenticatedUser .
	 */
	public $isAuthenticatedUser;// @codingStandardsIgnoreLine
	/**
	 * Kind variable with public access
	 *
	 * @var $kind .
	 */
	public $kind;
	/**
	 * Permission Id id with public access
	 *
	 * @var $permissionId .
	 */
	public $permissionId;// @codingStandardsIgnoreLine
	/**
	 * Picture Type id with protected access
	 *
	 * @var $pictureType .
	 */
	protected $pictureType = 'Google_Service_Drive_UserPicture';// @codingStandardsIgnoreLine
	/**
	 * Picture Data Type with protected access
	 *
	 * @var $pictureDataType .
	 */
	protected $pictureDataType = '';// @codingStandardsIgnoreLine
	/**
	 * This function is used to set display name.
	 *
	 * @param string $displayName .
	 */
	public function setDisplayName( $displayName ) {// @codingStandardsIgnoreLine
		$this->displayName = $displayName; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get display name
	 */
	public function getDisplayName() {
		return $this->displayName; // @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set email address.
	 *
	 * @param string $emailAddress .
	 */
	public function setEmailAddress( $emailAddress ) {// @codingStandardsIgnoreLine
		$this->emailAddress = $emailAddress;// @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get email address.
	 */
	public function getEmailAddress() {
		return $this->emailAddress;// @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set authenticated user.
	 *
	 * @param string $isAuthenticatedUser .
	 */
	public function setIsAuthenticatedUser( $isAuthenticatedUser ) {// @codingStandardsIgnoreLine
		$this->isAuthenticatedUser = $isAuthenticatedUser;// @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to get authenticated user.
	 */
	public function getIsAuthenticatedUser() {
		return $this->isAuthenticatedUser;// @codingStandardsIgnoreLine
	}
	/**
	 * This function is used to set kind
	 *
	 * @param string $kind .
	 */
	public function setKind( $kind ) {
		$this->kind = $kind;
	}
	/**
	 * This function is used to get kind
	 */
	public function getKind() {
		return $this->kind;
	}
	/**
	 * This function is used to set permission id.
	 *
	 * @param string $permissionId .
	 */
	public function setPermissionId( $permissionId ) {// @codingStandardsIgnoreLine.
		$this->permissionId = $permissionId;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get permission id.
	 */
	public function getPermissionId() {
		return $this->permissionId;// @codingStandardsIgnoreLine.
	}
	/**
	 * This function is used to get picture.
	 *
	 * @param Google_Service_Drive_UserPicture $picture .
	 */
	public function setPicture( Google_Service_Drive_UserPicture $picture ) {// @codingStandardsIgnoreLine.
		$this->picture = $picture;
	}
	/**
	 * This function is used to get picture.
	 */
	public function getPicture() {// @codingStandardsIgnoreLine.
		return $this->picture;
	}
}
/**
 * This class ise used to drive user picture.
 */
class Google_Service_Drive_UserPicture extends Google_Model {// @codingStandardsIgnoreLine.
	/**
	 * Variable for internal gapi mappings
	 *
	 * @var $internal_gapi_mappings .
	 */
	protected $internal_gapi_mappings = array();
	/**
	 * Variable for url
	 *
	 * @var $url .
	 */
	public $url;
	/**
	 * This function is used to set url.
	 *
	 * @param string $url .
	 */
	public function setUrl( $url ) {// @codingStandardsIgnoreLine.
		$this->url = $url;
	}
	/**
	 * This function is used to get url.
	 */
	public function getUrl() {// @codingStandardsIgnoreLine.
		return $this->url;
	}
}
