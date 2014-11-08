package io.lcs.poem.dao;

import android.content.Context;

import com.google.gson.Gson;
import com.google.gson.JsonArray;
import com.google.gson.reflect.TypeToken;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.ByteArrayOutputStream;
import java.io.FileInputStream;
import java.io.FileReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.lang.reflect.Type;
import java.util.List;

import io.lcs.poem.pojo.Poem;


/**
 * Created by john on 2014/11/8.
 */
public class PoemDao {
	List<Poem> poemList;

	public PoemDao( InputStream inputStream ) {
		Gson g = new Gson( );
		Type listType = new TypeToken<List<Poem>>(){}.getType();
		this.poemList = (List<Poem>) g.fromJson(new InputStreamReader( inputStream ), listType);
	}

	public List<Poem> getPoemList() {
		return poemList;
	}
}
