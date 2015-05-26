# TYPO3 CMS Data Interception Extension

## Register

Register table names in ```ext_localconf.php```

```
	RegistryService::instance()->onAll(
		'tx_myext_domain_model_project'
	);
	RegistryService::instance()->onAll(
		'tx_myext_domain_model_comment'
	);
	RegistryService::instance()->onModify(
		'tx_myext_domain_model_comment',
		'Somebody\\MyExt\\Hook\\InterceptionHook->resolveProjectOnAddingComment'
	);
```

## Access intercepted data

At any point, use ```MonitorService``` to access intercepted data.

The following example adds tags to the pages cache with adding the meaning,
that e.g. the record of table ```tx_myext_domain_model_project``` with identifier
```123``` is accessed/rendered on that particular page.

The tag name would look like this in the example ```tx_myext_domain_model_project-123```.

```
	protected function applyPageCacheTags(TypoScriptFrontendController $frontendController) {
		$tags = array();

		/** @var IdentifierCollection $identifierCollection */
		foreach (MonitorService::instance()->getFetchCollection() as $identifierCollection) {
			$tagPrefix = $identifierCollection->getTableName() . '-';
			$tags[] = $tagPrefix;
			$tags = array_merge($tags, $identifierCollection->getPrefixedArrayCopy($tagPrefix));
		}

		$frontendController->addCacheTags($tags);
	}
```