# DigaAIDemoData
Demo Data generation based on the SwagPlatformDemoData Plugin

Right now, the Plugin uses a demo Data set from OpenAI to generate the Categories.

To try out the generation:<br>
```Ruby
Usage:
  dataai:create [options] [--] [<branche>]

Arguments:
  branche                Shop branche of the Demoshop

Options:
      --apikey=API-KEY   Your secrete Open API key
      --root=ROOT        The amount of root categories to generate
      --sub=SUB          The amount of sub categories to generate
```



<div style="background-color: rgb(13, 17, 23)">

![DigaAIDDClassDiagram](dev/DigaADD%20Class%20Diagram%20Decorationg.drawio.svg)

</div>

---
---

# Shopware Demo Data Plugin
Do not use in production environments!

Demo data plugin for Shopware 6. The data is imported on plugin activation and it will overwritten existing data.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Get started with Demo Data:
- Download the zip file, upload the extension in the Shopware administration and install and activate it.
- Or clone the git repository into the custom/plugins directory of your Shopware 6 installation and run `bin/console plugin:refresh && bin/console plugin:install --activate SwagPlatformDemoData` in the Shopware root directory to install the plugin.

