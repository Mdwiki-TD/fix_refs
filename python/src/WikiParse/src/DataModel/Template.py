"""
Template class

Represents a template in a wikitext document.
"""

from src.WikiParse.src.DataModel.Parameters import Parameters


class Template:
    """
    Represents a template in a wikitext document.
    """
    
    def __init__(self, name, parameters=None, originalText=""):
        """
        Initialize Template
        
        Args:
            name: The template name
            parameters: Dictionary or list of parameters
            originalText: The original text
        """
        self.name = name
        self.nameStrip = name.replace('_', ' ').strip()
        self.originalText = originalText
        
        if parameters is None:
            parameters = {}
        
        if isinstance(parameters, Parameters):
            self.parameters = parameters
        else:
            self.parameters = Parameters(parameters)
    
    def getStripName(self):
        """Get the name stripped of any underscores"""
        return self.nameStrip
    
    def getName(self):
        """Get the template name"""
        return self.name
    
    def getOriginalText(self):
        """Get the original, unprocessed text"""
        return self.originalText
    
    def getParameters(self):
        """Get the parameters"""
        return self.parameters.getParameters()
    
    def deleteParameter(self, key):
        """Delete a parameter"""
        self.parameters.delete(key)
    
    def getParameter(self, key):
        """Get a parameter value"""
        return self.parameters.get(key)
    
    def setName(self, name):
        """Set the template name"""
        self.name = name
        self.nameStrip = name.replace('_', ' ').strip()
    
    def setParameter(self, key, value):
        """Set a parameter"""
        self.parameters.set(key, value)
    
    def changeParameterName(self, old, new):
        """Change a parameter name"""
        self.parameters.changeParameterName(old, new)
    
    def changeParametersNames(self, params_new):
        """Change multiple parameter names"""
        self.parameters.changeParametersNames(params_new)
    
    def toString(self, newLine=False, ljust=0):
        """
        Convert the template to a string
        
        Args:
            newLine: Whether to use newlines
            ljust: Left justify width
            
        Returns:
            The template as a string
        """
        separator = "\n" if newLine else ""
        template_name = self.name.strip() if newLine else self.name
        
        result = "{{" + template_name + separator
        result += self.parameters.toString(ljust, newLine)
        result += separator + "}}"
        
        return result
    
    def __str__(self):
        """String representation"""
        return self.toString()
