========
Metadata
========

Because Doctrine entities do not extend any smart ancestor class, we have to tell
Doctrine how to map the data from the database into the entity. There are
multiple ways of doing this.


Attributes
==========

Attributes mean that you will use attributes
to indicate the column mappings.

.. code-block:: php

 namespace App\Doctrine\ORM\Entity;

  use Doctrine\ORM\Mapping as ORM;

  #[ORM\Entity]
  #[ORM\Table(name: "articles")]
  class Article
  {
      #[ORM\Id]
      #[ORM\Column(type: "integer")]
      #[ORM\GeneratedValue(strategy: "AUTO")]
      private $id;

      #[ORM\Column(type: "string", nullable: false)]
      private $title;
  }


More about the attributes driver:
https://www.doctrine-project.org/projects/doctrine-orm/en/2.11/reference/attributes-reference.html


XML
===

Another option are XML mappings. It's better to change the
metadata paths to something like ``config_path('doctrine_orm_metadata')``
for your xml files.

App.Doctrine.ORM.Entity.Article.dcm.xml

.. code-block:: xml

  <?xml version="1.0"?>
  <doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping https://www.doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
    <entity name="App\Doctrine\ORM\Entity\Article" table="articles">
      <id name="id" type="integer">
        <generator strategy="AUTO"/>
      </id>
      <field name="title" type="string" nullable="false"/>
    </entity>
  </doctrine-mapping>


More information about XML mappings:
https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/xml-mapping.html


.. role:: raw-html(raw)
   :format: html

.. include:: footer.rst
