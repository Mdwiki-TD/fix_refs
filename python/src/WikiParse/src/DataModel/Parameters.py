"""
Parameters class

Represents template parameters in a wikitext document.
"""


class Parameters:
    """
    Represents template parameters in a wikitext document.
    """
    
    def __init__(self, parameters=None):
        """
        Initialize Parameters
        
        Args:
            parameters: Dictionary of parameters
        """
        self.parameters = parameters if parameters is not None else {}
    
    def getParameters(self):
        """Get the parameters of the template"""
        return self.parameters
    
    def delete(self, key):
        """
        Delete a parameter of the template
        
        Args:
            key: The key of the parameter to delete
        """
        if key in self.parameters:
            del self.parameters[key]
    
    def get(self, key, default=""):
        """
        Get a parameter of the template
        
        Args:
            key: The key of the parameter to get
            default: Default value if key doesn't exist
            
        Returns:
            The value of the parameter
        """
        return self.parameters.get(key, default)
    
    def has(self, key):
        """
        Check if a parameter of the template exists
        
        Args:
            key: The key of the parameter to check
            
        Returns:
            True if the parameter exists, false otherwise
        """
        return key in self.parameters
    
    def set(self, key, value):
        """
        Set a parameter of the template
        
        Args:
            key: The key of the parameter to set
            value: The value of the parameter
        """
        self.parameters[key] = value
    
    def changeParametersNames(self, map_dict):
        """
        Change the names of multiple parameters of the template
        
        Args:
            map_dict: Dictionary mapping old names to new names
        """
        new_parameters = {}
        
        # Use iteration to keep the order
        for k, v in self.parameters.items():
            if k in map_dict:
                new_key = map_dict[k]
                # new key has priority in case of name duplication
                new_parameters[new_key] = v
            elif k not in map_dict.values():
                # ignore keys that will be replaced later
                new_parameters[k] = v
            else:
                # ignore keys that don't exist in the map
                new_parameters[k] = v
        
        self.parameters = new_parameters
    
    def changeParameterName(self, old, new):
        """
        Change the name of a parameter of the template
        
        Args:
            old: The old name of the parameter
            new: The new name of the parameter
        """
        self.changeParametersNames({old: new})
    
    def str_pad_right(self, string, length, pad=" "):
        """
        Pad string on the right
        
        Args:
            string: String to pad
            length: Target length
            pad: Padding character
            
        Returns:
            Padded string
        """
        diff = length - len(string)
        return string + (pad * diff) if diff > 0 else string
    
    def toString(self, ljust=0, newLine=False):
        """
        Convert parameters to string
        
        Args:
            ljust: Left justify width
            newLine: Whether to add newlines
            
        Returns:
            String representation
        """
        separator = "\n" if newLine else ""
        result = ""
        index = 1
        
        for key, value in self.parameters.items():
            formatted_value = value.strip() if newLine else value
            
            if index == key:
                result += f"|{formatted_value}"
            else:
                formatted_key = self.str_pad_right(str(key), ljust) if ljust > 0 else str(key)
                result += f"{separator}|{formatted_key}={formatted_value}"
            index += 1
        
        return result.strip()
    
    def __str__(self):
        """String representation"""
        return self.toString()
