using UnityEngine;
using System.Collections;
using UnityEngine.UI;

namespace kv
{
    public class bind : MonoBehaviour
    {
        public string key;

        void Start()
        {
            refresh();
            keyvalue.on_refresh += refresh;
        }

        private void refresh()
        {
            Text ui = GetComponent<Text>();
            if (ui)
            {
                ui.text = keyvalue.get(key);
            }
        }
    }
}

