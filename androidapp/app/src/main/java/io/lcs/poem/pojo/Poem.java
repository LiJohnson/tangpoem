package io.lcs.poem.pojo;

import java.util.List;

/**
 * Created by john on 2014/11/8.
 */
public class Poem extends BasePojo {
	private String title;
	private String name;
	private String type;
	private List<String> content;

	public void setTitle(String title) {
		this.title = title;
	}

	public void setName(String name) {
		this.name = name;
	}

	public void setType(String type) {
		this.type = type;
	}

	public void setContent(List<String> content) {
		this.content = content;
	}

	public String getTitle() {
		return title;

	}

	public String getName() {
		return name;
	}

	public String getType() {
		return type;
	}

	public List<String> getContent() {
		return content;
	}
}
