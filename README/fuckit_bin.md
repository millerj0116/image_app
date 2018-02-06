Gets all of the posts, and any tags they have
TODO: Figure out how to incorporate this into the home page loop

SELECT posts.title, tags.name
FROM posts
	LEFT JOIN post_tags
    ON ( posts.post_id  = post_tags.post_id)
	LEFT JOIN tags
    ON ( tags.tag_id = post_tags.tag_id )

WHERE is_published = 1
