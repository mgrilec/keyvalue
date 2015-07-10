using System;
using UnityEngine;
using System.Collections;
using System.Collections.Generic;
using System.Runtime.CompilerServices;
using SimpleJson;
using UnityEngine.Networking;
using Random = UnityEngine.Random;

namespace kv
{
    public class keyvalue : MonoBehaviour
    {
        public string API;
        public string ProjectID;
        public float CacheTime;

        private long lastCached;

        public static Dictionary<string, string> db;

        private static keyvalue instance;
        private static bool refreshing;

        public static event Action on_refresh;

        void Awake()
        {
            if (instance == null)
            {
                instance = this;
                db = new Dictionary<string, string>();
                DontDestroyOnLoad(gameObject);
            }
            else
            {
                Destroy(gameObject);
            }
        }

        void Start()
        {

        }

        void Update()
        {
            long ticks = DateTime.Now.Ticks;
            if (DateTime.Now.Subtract(new DateTime(lastCached)).Seconds > CacheTime)
            {
                StartCoroutine(refresh());
            }
        }

        public static void Refresh()
        {
            instance.StartCoroutine(instance.refresh());
        }

        private IEnumerator refresh()
        {
            if (refreshing)
                yield break;

            Debug.Log("keyvalue: refresh started");
            refreshing = true;
            WWW www = new WWW(instance.API + "/keys/" + instance.ProjectID);
            while (!www.isDone)
                yield return new WaitForEndOfFrame();

            lastCached = DateTime.Now.Ticks;

            // parse response
            var response = (JsonObject)SimpleJson.SimpleJson.DeserializeObject(www.text);
            var results = (JsonObject)response["result"];
            foreach (var result in results)
            {
                Debug.Log("keyvalue: (" + result.Key + ", " + result.Value + ")");
                db[result.Key] = (string)result.Value;
            }

            // trigger callback
            if (on_refresh != null)
                on_refresh();

            refreshing = false;
            Debug.Log("keyvalue: refresh ended");
        }

        public static string get(string key)
        {
            if (db != null && db.ContainsKey(key))
                return db[key];

            return string.Empty;
        }
    }
}
