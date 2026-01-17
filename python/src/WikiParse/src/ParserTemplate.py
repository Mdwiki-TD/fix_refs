"""
ParserTemplate class

Parses a template text into its components: name and parameters.
"""

import re
from src.WikiParse.src.DataModel.Template import Template


class ParserTemplate:
    """
    Parses a template text into its components: name and parameters.
    """

    def __init__(self, templateText):
        """
        Initialize ParserTemplate

        Args:
            templateText: The template text to parse
        """
        self.templateText = templateText.strip()
        self.name = ""
        self.parameters = {}
        self.pipe = "|"
        self.pipeR = "-_-"
        self.parse()

    def clear_pipes(self, DTemplate):
        """
        Clear pipes in nested templates and links

        Args:
            DTemplate: Template string to process

        Returns:
            Processed template string
        """
        # Find nested templates
        matches = re.findall(r'\{\{(.*?)\}\}', DTemplate, re.DOTALL)

        for match in matches:
            DTemplate = DTemplate.replace(match, match.replace(self.pipe, self.pipeR))

        # Find links
        matches2 = re.findall(r'\[\[(.*?)\]\]', DTemplate)
        for match in matches2:
            DTemplate = DTemplate.replace(match, match.replace(self.pipe, self.pipeR))

        return DTemplate

    def parse(self):
        """Parse the template text to extract the template name and parameters"""
        match = re.match(r'^\{\{(.*?)(\}\})$', self.templateText, re.DOTALL)

        if match:
            DTemplate = self.clear_pipes(match.group(1))

            params = DTemplate.split("|")
            pipeR = self.pipeR
            pipe = self.pipe

            params = [p.replace(pipeR, pipe) for p in params]

            data = {}

            self.name = params[0]

            for i in range(1, len(params)):
                param = params[i]
                if "=" in param:
                    parts = param.split("=", 1)
                    key = parts[0].strip()
                    value = parts[1].strip()
                    data[key] = value
                else:
                    data[i] = param

            self.parameters = data

    def getTemplate(self):
        """
        Creates a Template object from the parsed template name and parameters

        Returns:
            Template object representing the parsed template data
        """
        return Template(self.name, self.parameters, self.templateText)
