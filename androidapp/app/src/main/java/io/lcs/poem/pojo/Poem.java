package io.lcs.poem.pojo;

import java.util.List;

/**
 * Created by john on 2014/11/8.
 */
public class Poem extends BasePojo {
	private long poemId;
	private String title;
	private String name;
	private String type;
	private List<String> content;

	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getType() {
		return type;
	}

	public void setType(String type) {
		this.type = type;
	}

	public List<String> getContent() {
		return content;
	}

	public void setContent(List<String> content) {
		this.content = content;
	}

	public long getPoemId() {

		return poemId;
	}

	public void setPoemId(long poemId) {
		this.poemId = poemId;
	}
}
