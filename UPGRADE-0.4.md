UPGRADE FROM 0.3 TO 0.4
=======================

This component is now a Composer Plugin that is automatically executed on
`post-install-cmd` and `post-update-cmd` events. So you must remove the
previously registered script from your composer.json file if is still there.

```diff
--- 0.3/composer.json   2021-05-07 09:57:18.360047977 +0200
+++ 0.4/composer.json   2021-05-07 09:57:31.928102623 +0200
@@ -1,10 +1,8 @@
 {
     "scripts": {
         "post-install-cmd": [
-            "Ajgl\\Composer\\ScriptSymlinker::createSymlinks"
         ],
         "post-update-cmd": [
-            "Ajgl\\Composer\\ScriptSymlinker::createSymlinks"
         ]
     }
 }
```
