# My Heritage er spam
	rt ls -i "Status='new' and (
		Requestor.EmailAddress LIKE 'myheritage' or 
		Requestor.EmailAddress like 'googlebase-noreply@google.com' or
		Requestor.EmailAddress like 'updates-noreply@linkedin.com'

	)"|rt edit - set Queue=Spam set Status=rejected


